<?php

namespace App\Services;

use App\Interfaces\LinkInterface;
use App\Models\Link;

class LinkService implements LinkInterface
{
    private $link;

    public function __construct(Link $link)
    {
        $this->link = $link;
    }

    public function link($code)
    {
        $data = [];
        $link = $this->link->where('code', $code)->first();
        if ($link) {
            if ($link->url == null) {
                $data = [
                    'url' => config('app.front') . $link->project->slug,
                    'is_external' => false,
                    'project_sku' => $link->project->sku,
                    'project_id' => $link->project->id,
                ];
            } else {
                $data = [
                    'url' => $link->url,
                    'is_external' => true,

                ];
            }
        }

        return $data;
    }

}
