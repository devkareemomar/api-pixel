<?php

namespace App\Models;

use App\Traits\Filter;
use App\Traits\Sort;
use App\Traits\Visitable;
use DB;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Visitable;
    use Filter;
    use Sort;

    protected $fillable = [
        'name',
        'slug',
        'sku',
        'creator_id',
        'total_earned',
        'total_wanted',
        'project_status_id',
        'description',
        'short_description',
        'featured',
        'thumbnail',
        'start_date',
        'end_date',
        'order',
        'visibility',
        'accept_donation',
        'show_in_home_page',
        'show_in_shop',
        'is_gift',
        'category_id',
        'sub_category_id',
        'active',
        'show_in_menu',
        'hidden',
        'donation_available',
        'country_id',
        'show_donation_comment',
        'donation_comment',
        'is_zakat',
        'show_donor_phone',
        'donor_phone_required',
        'show_donor_name',
        'donor_name_required',
        'show_banner',
        'is_continuous',
        'is_full_unit',
        'is_multi_country',
        'is_stock',
        'is_quick_donation',
        'unit_value',
        'stock',
        'show_timer',
        'show_target_amount',
        'show_paid_amount',
        'show_percentage',
        'main_image',
        'banner_image',
        'highlighted',
        'is_project_case',
        'suggested_values',
        'video'
    ];

    protected $dates = ['start_date', 'end_date'];

    public function languageProject()
    {
        return $this->hasMany(LanguageProject::class);
    }

    public function countries()
    {
        return $this->belongsToMany(Country::class)->select('countries.id', 'name', 'flag')->withPivot('total_wanted', 'suggested_values');
    }

    public function country()
    {
        return $this->belongsTo(Country::class)->select('countries.id', 'name', 'flag');
    }

    public function favorite()
    {
        return $this->belongsToMany(User::class);
    }

    public function project_gallery()
    {
        return $this->hasMany(ProjectGallery::class);
    }

    protected $hidden = ['pivot'];

    public function albums()
    {
        return $this->belongsToMany(Album::class, 'album_projects');
    }

    public function review()
    {
        return $this->hasMany(Review::class, 'project_id', 'id');
    }

    public function earnedPercentage(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->total_wanted == 0) {
                    return 0;
                }

                return number_format(($this->total_earned / $this->total_wanted) * 100, 0);
            }
        );
    }

    public function totalRemains(): Attribute
    {
        return Attribute::make(
            get: function () {
                return number_format((int)$this->total_wanted - (int)$this->total_earned);
            }
        );
    }

    public function getTotalRemainsAttribute()
    {
        return (int)$this->total_wanted - (int)$this->total_earned;
    }

    public function totalCollected(): Attribute
    {

        return Attribute::make(
            get: function () {
                $currency = Currency::find(request()->currency_id ?: 0);
                return number_format($this->total_earned * ($currency?->exchange_rate ?? 1)) . ' ' . $currency?->code;
            }
        );
    }

    public function getAllTranslation()
    {
        return $this->languageProject()->select('lang_code', 'name')->get()->groupBy('lang_code');
    }

    public function images()
    {
        return $this->hasMany(ProjectImage::class);
    }

    public function status()
    {
        return $this->belongsTo(ProjectStatus::class, 'project_status_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function album()
    {
        return $this->belongsToMany(Album::class, 'album_projects');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function languages()
    {
        return $this->belongsToMany(Language::class);
    }

    public function languageProjects()
    {
        return $this->hasMany(LanguageProject::class);
    }

    public function getRelatedProjects()
    {
        return DB::table('projects')
            ->where('id', '<>', $this->id)
            ->where('category_id', $this->category_id)
            ->where('category_id', '<>', null)
            ->select('id', 'name', 'thumbnail', 'slug')
            ->get();
    }
}
