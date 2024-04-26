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
     * Fetches all responses without a created_at date and assigns them random dates between
     * now and the latest date in the database
     *
     * @return void
     */
    public function randomizeCurrentBatch()
    {
        try {
            $batchQuery = Response::query()->whereNull('created_at');

            if ($batchQuery->count() < config('responses.randomizedTimestampSampleSize')) {
                return;
            }

            $max = now();
            $min = Carbon::parse(Response::query()->whereNotNull('created_at')->max('created_at'));

            $batchQuery->inRandomOrder()->each(function (Response $response, int $index) use ($max, $min) {
                // Give one random element the $max date, so that the next batch starts after now
                $response->created_at = $index === 0 ? $max : $min->copy()->addSeconds(random_int(0, $max->diffInSeconds($min)));
                $response->save();
            });
        } catch (\Exception $exception) {
            Log::error('Error randomizing current batch', ['exception' => $exception]);

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
