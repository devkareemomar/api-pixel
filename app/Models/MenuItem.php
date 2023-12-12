<?php

namespace App\Models;

use App\Traits\Filter;
use App\Traits\Sort;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use Sort;
    use Filter;

    protected $table = 'menu_items';


    protected $fillable = [
        'name', 'menu_id', 'parent_id', 'label', 'type', 'link', 'custom_url', 'sort', 'icon', 'image', 'is_mega'
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function subs()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('sort', 'ASC');
    }

    public function getall($id)
    {
        return $this->where("menu_id", $id)->orderBy("sort", "ASC")->get();
    }

    public static function getNextSortRoot($menu)
    {
        return self::where('menu_id', $menu)->max('sort') + 1;
    }

    public function child()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('sort', 'ASC');
    }

    public function childrenImages()
    {
        // get all images of children
        $images = $this->subs()->where('image', '!=', null)->get()->pluck('image')->toArray();
        return $images;
    }

    public function getLinkAttribute($value)
    {
        if($this->type == 'page') {
            $page = SitePage::find($this->page_id);
            return '/page/' . $page?->key;
        } elseif($this->type == 'project') {
            $project = Project::find($this->page_id);
            return '/projects/' . $project?->slug;
        } elseif($this->type == 'news') {
            $news = News::find($this->page_id);
            return '/news/' . $news?->slug;
        } elseif($this->type == 'form') {
            $form = FormBuilder::find($this->page_id);
            return '/forms/' . $form?->id;
        } else {
            return $value;
        }
    }

    public function getImageAttribute($value)
    {
        return $value ? config('app.dashboard') . $value : null;
    }
}
