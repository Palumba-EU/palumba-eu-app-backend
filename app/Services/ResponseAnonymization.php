<?php

namespace App\Services;

use App\Models\Response;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ResponseAnonymization
{
    /**
     * Generates a random datetime between now and the lowest date from the last {randomizedTimestampSampleSize} responses.
     * This should ensure that no single response can be associated with an entry in e.g. the servers access log and
     * therefore potentially an IP address, while still resulting in a useful timeline.
     */
    public function getRandomizedCreatedAtDate(): ?Carbon
    {
        try {
            $minCreatedAt = Response::query()
                ->whereNotNull('created_at')
                ->orderByDesc('created_at')
                ->limit(config('responses.randomizedTimestampSampleSize'))
                ->min('created_at');

            $max = Carbon::now();
            $min = Carbon::parse($minCreatedAt);

            return $min->addSeconds(random_int(0, $max->diffInSeconds($min)));
        } catch (\Exception $exception) {
            Log::error('Error creating random timestamp', ['exception' => $exception]);

            return null;
        }
    }

    /**
     * Hashes the users ip with a regularly changing salt.
     * This is to ensure that we can detect and filter spam answers afterward the fact.
     */
    public function getHashedIp(Request $request): ?string
    {
        $ip = $request->ip();

        if (is_null($ip)) {
            return null;
        }

        $salt = Cache::remember('response_salt', config('responses.saltRotationDuration'), function () {
            return Str::random(32);
        });

        return $salt.'/'.hash('sha512', $salt.$ip);
    }
}
