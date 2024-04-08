<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateResponseRequest;
use App\Models\Response;
use Illuminate\Support\Facades\DB;

class ResponseController extends Controller
{
    public function store(CreateResponseRequest $request): \Illuminate\Http\Response
    {
        $data = collect($request->validated());
        $answers = collect($data->get('answers'))->keyBy('statement_id')->toArray();

        DB::transaction(function () use ($data, $answers) {
            $response = new Response($data->only(['age', 'country_id', 'language_id', 'gender'])->toArray());
            $response->save();
            $response->statements()->sync($answers);
        });

        return \response('', 201);
    }
}
