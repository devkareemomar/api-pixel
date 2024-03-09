<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftTemplate extends Model
{
    use HasFactory;
    public $fillable = ['watermark_image','original_image'];
}
