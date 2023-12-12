<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FormResource extends JsonResource
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
            'project_name' => $this->project?->name,
            'short_description' => $this->project?->short_description,
            'description' => $this->project?->description,
            'status_name' => $this->status_name,
            'form_data' => $this->form_data,
            'locale' => $this->locale
        ];
    }
}
