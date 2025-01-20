<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateResponseRequest;
use App\Http\Requests\UpdateResponseRequest;
use App\Http\Resources\ResponseResource;
use App\Models\Election;
use App\Models\Language;
use App\Models\Response;
use App\Services\CrowdInTranslation;
use App\Services\ResponseAnonymization;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class ResponseController extends Controller
{
    public function store(CreateResponseRequest $request, ResponseAnonymization $anonymization, CrowdInTranslation $crowdin): ResponseResource
    {
        $data = collect($request->validated());
        $answers = $this->getAnswers($request);

        $languageId = $request->validated('language_id');
        $languageCode = $request->validated('language_code');

        $language = ! is_null($languageId)
            ? Language::query()->where('id', $languageId)->firstOrFail()
            : Language::query()->where('code', $languageCode)->firstOrFail();

        $election = $request->has('election_id') ? Election::query()->findOrFail($request->get('election_id')) : Election::default();

        $response = new Response([
            ...$data->only(['age', 'country_id', 'gender', 'level_of_education'])->toArray(),
            'election_id' => $election->id,
            'language_code' => $language->code,
            'created_at' => null,
            'hashed_ip_address' => $anonymization->getHashedIp($request),
            'editable_until' => Carbon::now()->addHours(config('responses.editableTime')),
        ]);

        DB::transaction(function () use ($response, $answers) {
            $response->save();
            $response->statements()->sync($answers);
        });

        $anonymization->randomizeCurrentBatch();

        return new ResponseResource($response);
    }

    public function update(UpdateResponseRequest $request, Response $response): \Illuminate\Http\Response
    {
        if ($response->editable_until->isPast()) {
            abort(403, 'This answer can no longer be changed');
        }

        $answers = $this->getAnswers($request);
        $response->statements()->syncWithoutDetaching($answers);

        return \response('', 200);
    }

    private function getAnswers(FormRequest $request)
    {
        return collect($request->validated('answers'))->map(fn ($a) => [
            ...$a,
            // Internally we work with integers, so the scale becomes [-2, -1, 0, +1, +2]
            'answer' => ! is_null($a['answer']) ? intval($a['answer'] * 2) : null,
        ])->keyBy('statement_id')->toArray();
    }
}
