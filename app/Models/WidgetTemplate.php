<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WidgetTemplate extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'category_id', 'css_path', 'js_path', 'html_path', 'thumbnail'];

    public function category()
    {
        return $this->belongsTo(WidgetCategory::class);
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class)
            ->withPivot(['id'])
            ->withTimestamps();
    }
}
