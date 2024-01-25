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
       
        $donation_available = 0;
        if($this->donation_available == 1){
            $donation_available = 1;

            if($this->is_continuous == 1 && $this->getTotalRemainsAttribute() == 0){
                $donation_available = 1;
            }elseif($this->is_continuous != 1  && $this->getTotalRemainsAttribute() == 0){
                $donation_available = 0;
            }
        }


        $suggested = null; 
        if(isset($this->suggested_values) && $this->suggested_values != ""){
            foreach(json_decode($this->suggested_values,true) as $key => $value) {
                
                $suggested[] = ($this->is_full_unit == 1) ? ['lable' =>$key,'value' =>$value] : ['value' =>$value]; 
            }
        }

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
            'donation_available' => (int)$donation_available,
            'is_full_unit' => (int)$this->is_full_unit,
            'suggested_values' => isset($this->suggested_values) ?  $suggested : null,

        ];
    }
}
