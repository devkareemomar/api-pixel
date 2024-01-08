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
            'main_image' => $this->main_image ? config('app.dashboard') . $this->main_image : null,
            'video' => $this->video,
            'name' => $this->getDefaultAttribute('name') ,
            'slug' => $this->getDefaultAttribute('slug'),
            'total_wanted' => number_format((float)$this->total_wanted, 0, '.', ','),
            'total_collected' => number_format((float)$this->total_collected, 0, '.', ','),
            'total_earned' => number_format((float)$this->total_earned, 0, '.', ','),
            'earned_percentage' => $this->earnedPercentage(),
            'total_remains' => $this->getTotalRemainsAttribute(),
        ];
    }
}
