<?php

namespace App\Http\Controllers;

use App\Http\Resources\NewsResource;
use App\Interfaces\InitRequestInterface;
use App\Interfaces\NewsInterface;
use Illuminate\Http\Request;

class InitRequestController extends BaseApiController
{
    private $init;

    public function __construct(InitRequestInterface $init)
    {
        $this->init = $init;
    }

    public function init(Request $request)
    {
        $init = $this->init->init($request);
        return response()->json(['data'=> $init,], 200);

    }


}
