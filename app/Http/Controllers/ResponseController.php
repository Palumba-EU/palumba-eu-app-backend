<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateResponseRequest;
use App\Models\Language;
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

        $languageId = $request->validated('language_id');
        $languageCode = $request->validated('language_code');

        $language = ! is_null($languageId)
            ? Language::query()->where('id', $languageId)->firstOrFail()
            : Language::query()->where('code', $languageCode)->firstOrFail();

        DB::transaction(function () use ($language, $anonymization, $request, $data, $answers) {
            $response = new Response([
                ...$data->only(['age', 'country_id', 'gender'])->toArray(),
                'language_code' => $language->code,
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
