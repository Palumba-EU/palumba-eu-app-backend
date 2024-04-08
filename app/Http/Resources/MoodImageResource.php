<?php

namespace App\Http\Resources;

use App\Models\MoodImage;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

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
            'text' => $this->link_text,
            'image' => Storage::url($this->image),
        ];
    }
}
