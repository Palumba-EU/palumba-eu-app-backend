<?php

namespace App\Http\Resources;

use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/** @mixin Topic */
class TopicResource extends JsonResource
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
            'icon' => Storage::disk('public')->url($this->icon),
            'color' => $this->color,
            'extreme1' => $this->getTranslationForAttribute('extreme1'),
            'extreme2' => $this->getTranslationForAttribute('extreme2'),
            'associated_statements' => $this->statements->pluck('id'),
        ];
    }
}
