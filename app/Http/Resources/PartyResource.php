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
            'images' => MoodImageResource::collection($this->mood_images),
            'link' => $this->link,
            'acronym' => $this->acronym,
            'answers' => AnswerResource::collection($this->statements),
            'positions' => $this->positions->map(fn ($w) => ['topic_id' => $w->id, 'position' => $w->pivot->position]),
            'in_parliament' => $this->in_parliament,
            'unavailable_in' => CountryResource::collection($this->unavailable_in_countries),

            'profile' => [
                'bio' => $this->profile_bio,
                'affiliation' => $this->profile_affiliation,
                'red_flags' => $this->profile_red_flags,
                'link1' => $this->profile_link1,
                'link1_text' => $this->profile_link1_text,
                'link2' => $this->profile_link2,
                'link2_text' => $this->profile_link2_text,
            ],

        ];
    }
}
