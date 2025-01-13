<?php

namespace App\Http\Resources;

use App\Models\Election;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Election */
class ElectionResource extends JsonResource
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
            'date' => $this->date->toDateString(),
            'country' => new CountryResource($this->country),
        ];
    }
}
