<?php

namespace App\Http\Resources;

use App\Models\CountryProject;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectAllResource extends JsonResource
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
            'name' => $this->name ?? $this->project_name,
            'slug' => $this->slug ?? $this->project_slug,
            'short_description' => $this->short_description ?? $this->project_short_description,
            'total_earned' => number_format((float)$this->total_earned, 0, '.', ','),
            'total_collected' => number_format((float)$this->total_collected, 0, '.', ','),
            'total_wanted' => number_format((float)$this->total_wanted, 0, '.', ','),
            'thumbnail' => $this->thumbnail ? config('app.dashboard') . $this->thumbnail : null,
            'show_in_home_page' => (int)$this->show_in_home_page,
            'main_image' => $this->main_image ? config('app.dashboard') . $this->main_image : null,
            'video' => $this->video,
            'banner_image' => $this->banner_image ? config('app.dashboard') . $this->banner_image : null,
            'category' => $this->category->name ?? '',
        ];
    }
}
