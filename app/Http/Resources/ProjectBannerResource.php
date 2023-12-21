<?php

namespace App\Http\Resources;

use App\Models\CountryProject;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectBannerResource extends JsonResource
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
            'name' => $this->getDefaultAttribute('name') ,
            'slug' => $this->getDefaultAttribute('slug'),
        ];
    }
}
