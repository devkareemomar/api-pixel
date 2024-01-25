<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FormStatusResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'order_number'     => $this->id,
            'status'           => $this->status,
            'price'            => $this->price,
            'checks_date'      => $this->checks_date,
        ];
    }
}
