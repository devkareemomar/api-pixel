<?php

namespace App\Http\Controllers;

use App\Interfaces\LinkInterface;


class LinkController extends BaseApiController
{
    private $link;

    public function __construct(LinkInterface $link)
    {
        $this->link = $link;
    }

    public function link($code)
    {
        $link = $this->link->link($code);
        return response()->json($link);
    }

}
