<?php

namespace App\Models;

use App\Traits\Sort;
use App\Traits\Visitable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
    use HasFactory, SoftDeletes;
    use Sort;
    use Visitable;

    protected $fillable = [
        'title', 'short_description', 'description', 'image', 'views'
    ];
    public function categories()
    {
        return $this->belongsToMany(NewsCategory::class,'category_news_categories','news_id','news_categories_id');
    }

    public function tags()
    {
        return $this->belongsToMany(NewsTag::class,'tag_news_tags','news_id','news_tags_id');
    }
}
