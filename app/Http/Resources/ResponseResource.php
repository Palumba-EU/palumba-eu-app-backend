<?php

namespace App\Http\Resources;

use App\Models\Response;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Response
 */
class ResponseResource extends JsonResource
{
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->uuid,
            'editable_until' => $this->editable_until->toDateTimeString(),
        ];
    }
}
