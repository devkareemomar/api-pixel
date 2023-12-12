<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $related = ($this->related_news==null) ? null:  RelatedNewsResource::collection($this->related_news);
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'image' => config('app.dashboard').$this->image,
            'created_at' => $this->created_at->format('Y.m.d'),
            'related_news'=>$related,
            'visits' => $this->visits()->count(),
        ];
    }
}
