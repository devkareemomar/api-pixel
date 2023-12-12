<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->project->id,
            'name' => $this->project->name ?? $this->project->project_name,
            'sku' => $this->project->sku,
            'slug' => $this->project->slug ?? $this->project->project_slug,
            'status' => $this->project->status,
            'short_description' => $this->project->short_description ?? $this->project->project_short_description,
            'translation' => $this->project->getAllTranslation(),
            'thumbnail' => $this->project->thumbnail ? config('app.dashboard') . $this->project->thumbnail : null,
            'amount' => $this->amount,
        ];
    }
}
