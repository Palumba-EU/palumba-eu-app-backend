<?php

namespace App\Http\Resources;

use App\Models\Party;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'name' => $this->name,
            'country' => new CountryResource($this->country),
            'color' => $this->color,
            'local_parties' => LocalPartyResource::collection($this->local_parties),
            'policies' => PolicyResource::collection($this->policies),
            'position' => $this->position,
        ];
    }
}
