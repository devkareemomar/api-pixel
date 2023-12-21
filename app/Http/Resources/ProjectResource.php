<?php

namespace App\Http\Resources;

use App\Models\CountryProject;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
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
            'name' => $this->getDefaultAttribute('name') ,
            'slug' => $this->getDefaultAttribute('slug'),
            'sku' => $this->sku,
            'status' => ['name'=>$this->status->name ?? '','color'=>$this->status->color ?? ''],
            'main_image' => $this->main_image ? config('app.dashboard') . $this->main_image : null,
            'video' => $this->video,



            'total_wanted' => number_format((float)$this->total_wanted, 0, '.', ','),
            'total_collected' => number_format((float)$this->total_collected, 0, '.', ','),
            'total_earned' => number_format((float)$this->total_earned, 0, '.', ','),
            'earned_percentage' => min($this->earned_percentage, 100),

            'category' => $this->category->name ?? '',
            'short_description' => $this->getDefaultAttribute('short_description'),
            'suggested_values' => explode(',',$this->suggested_values),

            'description' => $this->getDefaultAttribute('description'),


            'show_donor_phone' => (int)$this->show_donor_phone,
            'donor_phone_required' => (int)$this->donor_phone_required,
            'show_donor_name' => (int)$this->show_donor_name,
            'donor_name_required' => (int)$this->donor_name_required,
            'show_donation_comment' => (int)$this->show_donation_comment,

        ];
    }
}
