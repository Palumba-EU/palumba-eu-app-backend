<?php

namespace App\Http\Requests;

use App\Models\Enums\GoingToVote;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateResponseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'age' => ['present', 'nullable', 'integer', 'min:0'],
            'election_id' => ['sometimes', 'exists:elections,id'],
            'country_id' => ['required', 'exists:countries,id'],
            'language_id' => ['required_without:language_code', 'integer', Rule::exists('languages', 'id')->where(
                fn (Builder $query) => $query->where('published', '=', true)
            )],
            'language_code' => ['required_without:language_id', 'string', Rule::exists('languages', 'code')->where(
                fn (Builder $query) => $query->where('published', '=', true)
            )],
            'gender' => ['present', 'nullable', Rule::in(['male', 'female', 'gender-fluid', 'non-binary', 'diverse'])],
            'level_of_education' => ['sometimes', 'nullable', 'integer', 'min:0', 'max:8'],
            'going_to_vote' => ['sometimes', 'string', Rule::enum(GoingToVote::class)],
            'answers' => ['present', 'array'],
            'answers.*.statement_id' => ['required', 'distinct', Rule::exists('statements', 'id')->where(
                fn (Builder $query) => $query->where('published', '=', true)
            )],
            'answers.*.answer' => ['present', 'nullable', 'numeric', 'min:-1', 'max:1', Rule::in([-1, -0.5, 0, 0.5, 1])],
        ];
    }
}
