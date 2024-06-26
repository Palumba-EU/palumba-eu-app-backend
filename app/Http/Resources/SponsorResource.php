<?php

namespace App\Http\Resources;

use App\Models\Sponsor;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/** @mixin Sponsor */
class SponsorResource extends JsonResource
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
            'link' => $this->link,
            'banner_image' => $this->getTranslatedFile('banner_image'),
            'banner_link' => $this->banner_link,
            'banner_description' => $this->banner_description,
            'category' => $this->category,
        ];
    }
}
