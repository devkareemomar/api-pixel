<?php

namespace App\Models;

use App\Traits\Sort;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class NewsCategory extends Model
{
    use SoftDeletes, HasFactory;
    use Sort;

    protected $fillable = [
        'name', 'slug', 'featured','image', 'icon'
    ];

    protected function slug(): Attribute
    {
        return Attribute::make(
            set : fn (?string $value, array $attributes) => $value ? $value : Str::slug($attributes['name'])
        );
    }
}
