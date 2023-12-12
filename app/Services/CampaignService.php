<?php

namespace App\Services;

use App\Interfaces\CampaignInterface;
use App\Models\Campaign;

class CampaignService implements CampaignInterface
{
    private $campaign;

    public function __construct(Campaign $campaign)
    {
        $this->campaign = $campaign->where('is_active',1);
    }


    public function campaign($request)
    {
        $paginateNo = 6;
        if ($request->has('per_page')) {
            $paginateNo = $request->per_page;
        }
       return $this->campaign->select('id', 'title', 'description', 'slogan', 'image', 'start_date', 'end_date','is_home_slider',
                'created_at')
            ->paginate($paginateNo);
    }

    public function campaign_details($request, $campaign_id)
    {
        $data = [];
        $campaign_data =  $this->campaign->where('id',$campaign_id)->select('id', 'title', 'description', 'slogan', 'image', 'start_date', 'end_date','is_home_slider',
            'created_at')->first();

        if (empty($campaign_data)) {
            return $data;
        }
        return $campaign_data;
    }
}
