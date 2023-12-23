<?php

namespace App\Http\Resources;

use App\Models\Project;
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
        $projects = [];

            foreach($this->cartProjects as  $project){
                $projects[] = [

                    // 'project'         => ,
                    'project_id' => $project->project_id,
                    'project_slug' => $project->project->getDefaultAttribute('slug'),
                    'project_main_image' => $project->project->main_image ? config('app.dashboard') . $project->project->main_image : null,
                    'project_name' => $project->project->getDefaultAttribute('name') ,
                    'amount'          => $project->amount,
                    'gifted_to_email' => $project->gifted_to_email,
                    'gifted_to_phone' => $project->gifted_to_phone,
                    'gifted_to_name'  => $project->gifted_to_name,
                    'donor_comment'   => $project->donor_comment,
                ];
            }

        return [
            'cart_id' => $this->id,
            'total_amount' => $this->total_amount,
            'projects' => $projects,

        ];
    }
}
