<?php

namespace App\Http\Resources;

use App\Models\LocalParty;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/** @mixin LocalParty */
class LocalPartyResource extends JsonResource
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
            'logo' => Storage::disk('public')->url($this->logo),
            'description' => $this->getTranslationForAttribute('description'),
            'link' => $this->link,
            'link_text' => $this->getTranslationForAttribute('link_text'),
            'acronym' => $this->acronym,
            'country_id' => $this->country_id,
        ];
    }
}
