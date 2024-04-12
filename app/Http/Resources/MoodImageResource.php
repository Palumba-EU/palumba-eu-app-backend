<?php

namespace App\Http\Resources;

use App\Models\MoodImage;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin MoodImage */
class MoodImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'link' => $this->link,
            'text' => $this->getTranslationForAttribute('link_text'),
            'image' => $this->getTranslatedFile('image'),
        ];
    }
}
