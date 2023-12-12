<?php

namespace App\Http\Controllers;

use App\Http\Resources\NewsResource;
use App\Interfaces\NewsInterface;
use Illuminate\Http\Request;

class NewsController extends BaseApiController
{
    private $news;

    public function __construct(NewsInterface $news)
    {
        $this->news = $news;
    }

    public function news(Request $request)
    {
        $news = $this->news->news($request);
        return NewsResource::collection($news);
    }

    public function news_details(Request $request, $id)
    {
        $news = $this->news->news_details($request, $id);
        if (empty($news)) {
            return $this->return_fail(__('news.news_fail'), []);
        }
        $news->visit($request?->user()?->id);
        return NewsResource::make($news);
    }
}
