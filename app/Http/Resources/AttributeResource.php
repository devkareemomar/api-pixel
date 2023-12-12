<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttributeResource extends JsonResource
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
            'input_type' => $this->inputType->name,
            'min_quantity' => $this->min_quantity,
            'max_quantity' => $this->max_quantity,
            'has_order' => $this->has_order,
        ];    }
}
