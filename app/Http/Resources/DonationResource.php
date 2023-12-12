<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DonationResource extends JsonResource
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
            'tag_id' => $this->tag_id,
            'category_id' => $this->category_id,
            'project_id' => $this->project_id,
            'project_code' => $this->project_code,
            'transaction_id' => $this->transaction_id,
            'reference' => $this->reference,
            'payment_method' => $this->payment_method,
            'amount' => $this->amount,
            'paid_amount' => $this->paid_amount,
            'result' => $this->result,
            'user_id' => $this->user_id,
            'donor_name' => $this->donor_name,
            'donor_phone' => $this->donor_phone,
            'is_zakat' => $this->is_zakat,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
