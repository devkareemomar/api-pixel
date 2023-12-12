<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class AlbumMedia extends Model
{
    public $timestamps = false;

    protected $fillable = ['album_id', 'media_id'];
}
