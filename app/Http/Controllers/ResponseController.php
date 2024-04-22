<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateResponseRequest;
use App\Models\Response;
use App\Services\ResponseAnonymization;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ResponseController extends Controller
{
    public function store(CreateResponseRequest $request, ResponseAnonymization $anonymization): \Illuminate\Http\Response
    {
        $data = collect($request->validated());
        $answers = collect($data->get('answers'))->map(fn ($a) => [
            ...$a,
            // Internally we work with integers, so the scale becomes [-2, -1, 0, +1, +2]
            'answer' => ! is_null($a['answer']) ? intval($a['answer'] * 2) : null,
        ])->keyBy('statement_id')->toArray();

        DB::transaction(function () use ($anonymization, $request, $data, $answers) {
            $response = new Response([
                ...$data->only(['age', 'country_id', 'language_id', 'gender'])->toArray(),
                'created_at' => $anonymization->getRandomizedCreatedAtDate(),
                'hashed_ip_address' => $anonymization->getHashedIp($request),
            ]);
            $response->save();
            $response->statements()->sync($answers);
        });

        return \response('', 201);
    }
}
