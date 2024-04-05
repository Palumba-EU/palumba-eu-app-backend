<?php

namespace App\Http\Requests;

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
            'country_id' => ['required', 'exists:countries,id'],
            'language_id' => ['required', 'integer'],
            'gender' => ['present', 'nullable', Rule::in(['male', 'female', 'diverse'])],
            'answers' => ['present', 'array'],
            'answers.*.statement_id' => ['required', 'exists:statements,id', 'distinct'],
            'answers.*.answer' => ['present', 'nullable', 'integer', 'min:-2', 'max:2'],
        ];
    }
}
