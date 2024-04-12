<?php

namespace App\Http\Resources;

use App\Models\Party;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/** @mixin Party */
class PartyResource extends JsonResource
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
            'name' => $this->getTranslationForAttribute('name'),
            'color' => $this->color,
            'logo' => Storage::disk('public')->url($this->logo),
            'local_parties' => LocalPartyResource::collection($this->local_parties),
            'policies' => PolicyResource::collection($this->policies),
            'position' => $this->position,
            'images' => MoodImageResource::collection($this->mood_images),
            'link' => $this->link,
            'acronym' => $this->acronym,
            'answers' => AnswerResource::collection($this->statements),
        ];
    }
}
