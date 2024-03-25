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
            'name' => $this->name,
            'logo' => Storage::url($this->logo),
            'link' => $this->link,
        ];
    }
}
