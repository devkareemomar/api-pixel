<?php

namespace App\Services;

use App\Interfaces\NewsInterface;
use App\Models\News;
use Illuminate\Support\Facades\DB;

class NewsService implements NewsInterface
{
    private $news;

    public function __construct(News $news)
    {
        $this->news = $news->with(['tags' => function ($query) {
            $query->select('news_tags.id', 'name', 'slug');
        }])->with(['categories' => function ($query) {
            $query->select('news_categories.id', 'name', 'slug');
        }]);

    }

    public function news($request)
    {
        $paginateNo = 9;
        if ($request->has('per_page')) {
            $paginateNo = $request->per_page;
        }
        return $this->news
            ->select('id', 'title', 'slug', 'description', 'short_description', 'image', 'views', 'created_at')
            ->paginate($request->input('per_page') ?? $paginateNo);
    }

    public function news_details($request, $news_id)
    {
        $data = [];
        $news_data = $this->news
            ->where('slug', $news_id)
            ->firstOrFail();

        $categories = DB::table('category_news_categories')->where('news_id', $news_data->id)->pluck('news_categories_id')->toArray();

        $related_news = News::select('id', 'title', 'slug', 'description', 'short_description', 'image', 'views', 'created_at')->whereHas('categories', function ($q) use ($categories) {
            $q->whereIn('category_news_categories.news_categories_id', $categories);
        })
            ->where('id', '<>', $news_id)
            ->take(10)->get();
        $news_data['related_news'] = $related_news;
        if (empty($news_data)) {
            return $data;
        }
        return $news_data;
    }
}
