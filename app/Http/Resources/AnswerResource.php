<?php

namespace App\Http\Resources;

use App\Models\Statement;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Statement */
class AnswerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'statement_id' => $this->id,
            // Internally we work with integers (because they are easier to compare and work with than floats),
            // so the scala changes from
            // [-1, -0.5, 0, +0.5, +1]
            // to
            // [-2, -1, 0, +1, +2]
            'answer' => $this->pivot->answer / 2,
        ];
    }
}
