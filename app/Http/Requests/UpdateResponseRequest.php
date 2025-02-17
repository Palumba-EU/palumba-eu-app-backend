<?php

namespace App\Http\Requests;

use App\Models\Enums\GoingToVote;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateResponseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'going_to_vote' => ['sometimes', Rule::enum(GoingToVote::class)],
        ];
    }
}
