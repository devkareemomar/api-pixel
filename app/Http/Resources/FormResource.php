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
            'status_name' => $this->status_name,
            'project_name' => $this->project?->name,
            'short_description' => $this->project?->short_description,
            'description' => $this->project?->description,
            'form_data' => json_decode($this->form_data,true),
            'locale' => $this->locale
        ];
    }
}
