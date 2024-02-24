<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GiftTemplateResource extends JsonResource
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
            'watermark_image' => $this->watermark_image ? config('app.dashboard') . $this->watermark_image : null,
            'original_image' => $this->original_image ? config('app.dashboard') . $this->original_image : null,
        ];
    }
}
