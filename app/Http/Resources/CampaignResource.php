<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CampaignResource extends JsonResource
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
            'title' => $this->title,
            'image' => $this->image ? config('app.dashboard') . $this->image : null,
            'description' => $this->description,
            'slogan' => $this->slogan,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'is_home_slider' => $this->is_home_slider,
            'projects' => ProjectResource::collection($this->projects)

        ];
    }
}
