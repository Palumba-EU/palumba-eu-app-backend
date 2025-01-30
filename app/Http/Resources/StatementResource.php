<?php

namespace App\Http\Resources;

use App\Models\Statement;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Statement */
class StatementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'statement' => $this->getTranslationForAttribute('statement'),
            'details' => $this->getTranslationForAttribute('details'),
            'footnote' => $this->getTranslationForAttribute('footnote'),
            'emojis' => $this->emojis,
            'weights' => $this->weights->map(fn ($w) => ['topic_id' => $w->id, 'weight' => $w->pivot->weight]),
            'is_tutorial' => $this->is_tutorial,
        ];
    }
}
