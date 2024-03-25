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
            'name' => $this->name,
            'logo' => Storage::url($this->logo),
            'link' => $this->link,
            'banner_image' => Storage::url($this->banner_image),
            'banner_link' => $this->banner_link,
            'category' => $this->category,
        ];
    }
}
