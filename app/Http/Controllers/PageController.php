<?php

namespace App\Http\Controllers;

use App\Http\Resources\SitePageResource;
use App\Models\Page;
use App\Models\SitePage;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function show($id)
    {
        $page = SitePage::where([
            'key' => $id,
            'lang' => config('app.locale'),
        ])->firstOrFail();

        return SitePageResource::make($page);
    }
}
