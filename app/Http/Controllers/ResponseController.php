<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateResponseRequest;
use App\Models\Response;
use App\Services\CrowdInTranslation;
use App\Services\ResponseAnonymization;
use Illuminate\Support\Facades\DB;

class ResponseController extends Controller
{
    public function store(CreateResponseRequest $request, ResponseAnonymization $anonymization, CrowdInTranslation $crowdin): \Illuminate\Http\Response
    {
        $data = collect($request->validated());
        $answers = collect($data->get('answers'))->map(fn ($a) => [
            ...$a,
            // Internally we work with integers, so the scale becomes [-2, -1, 0, +1, +2]
            'answer' => ! is_null($a['answer']) ? intval($a['answer'] * 2) : null,
        ])->keyBy('statement_id')->toArray();

        $languageCode = $request->validated('language_code');

        // This is a purely temporary solution, so that the apps keep working until the next app update
        if (is_null($languageCode)) {
            $languageId = $request->validated('language_id');
            if ($languageId === 0) {
                $languageCode = 'en';
            } else {
                $languages = $crowdin->listTargetLanguages();
                $languageCode = $languages->where('id', '=', $languageId)->firstOrFail()['language_code'];
            }

        }

        DB::transaction(function () use ($languageCode, $anonymization, $request, $data, $answers) {
            $response = new Response([
                ...$data->only(['age', 'country_id', 'gender'])->toArray(),
                'language_code' => $languageCode,
                'created_at' => null,
                'hashed_ip_address' => $anonymization->getHashedIp($request),
            ]);
            $response->save();
            $response->statements()->sync($answers);
        });

        $anonymization->randomizeCurrentBatch();

        return \response('', 201);
    }
}
