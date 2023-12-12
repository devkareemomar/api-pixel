<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NewsTag extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug'];

    protected function slug(): Attribute
    {
        return Attribute::make(
            set : fn (?string $value, array $attributes) => $value ? $value : Str::slug($attributes['name'])
        );
    }
}
