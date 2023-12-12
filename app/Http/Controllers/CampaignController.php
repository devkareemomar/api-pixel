<?php

namespace App\Http\Controllers;

use App\Http\Resources\CampaignResource;
use App\Interfaces\CampaignInterface;
use Illuminate\Http\Request;

class CampaignController extends BaseApiController
{
    private $campaign;

    public function __construct(CampaignInterface $campaign)
    {
        $this->campaign = $campaign;
    }

    public function campaign(Request $request)
    {
        $campaigns = $this->campaign->campaign($request);
        return CampaignResource::collection($campaigns);
    }

    public function campaign_details(Request $request, $campaign_id)
    {
        $campaign = $this->campaign->campaign_details($request, $campaign_id);
        if (empty($campaign)) {
            return $this->return_fail(__('campaign.campaign_fail'), []);
        }
        return CampaignResource::make($campaign);

    }
}
