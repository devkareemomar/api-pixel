<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AlbumResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $imagesArray = explode(',', $this->images);
        return [
            'title' => $this->title,
            'description' => $this->description,
            'images' => array_map(function ($image) {return config('app.dashboard') . $image;}, $imagesArray),
            'videos' => $this->videos,
        ];
    }
}
