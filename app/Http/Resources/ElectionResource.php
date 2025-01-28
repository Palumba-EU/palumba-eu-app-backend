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

            'egg_screen' => [
                'title' => $this->getTranslationForAttribute('egg_title'),
                'description' => $this->getTranslationForAttribute('egg_description'),
                'image' => $this->getTranslatedFile('egg_image'),
                'yes_btn_text' => $this->getTranslationForAttribute('egg_yes_btn_text'),
                'yes_btn_link' => $this->getTranslationForAttribute('egg_yes_btn_link'),
                'no_btn_text' => $this->getTranslationForAttribute('egg_no_btn_text'),
            ],
        ];
    }
}
