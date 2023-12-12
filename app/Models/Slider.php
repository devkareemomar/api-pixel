<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'media_type', 'media_path'];

    public function mediaPath(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['media_type'] == 'image' ? asset('storage/' . $value) : $value
        );
    }
}
