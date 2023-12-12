<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Language extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'short_name', 'flag', 'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean'
    ];
}
