<?php

namespace App\Http\Requests;

use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateResponseAnswersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'answers' => ['present', 'array'],
            'answers.*.statement_id' => ['required', 'distinct', Rule::exists('statements', 'id')->where(
                fn (Builder $query) => $query->where('published', '=', true)
            )],
            'answers.*.answer' => ['present', 'nullable', 'numeric', 'min:-1', 'max:1', Rule::in([-1, -0.5, 0, 0.5, 1])],
        ];
    }
}
