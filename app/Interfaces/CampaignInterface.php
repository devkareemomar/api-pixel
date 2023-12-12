<?php

namespace App\Interfaces;

interface CampaignInterface
{
    public function campaign($request);

    public function campaign_details($request, $campaign_id);
}
