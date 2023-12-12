<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class AlbumLanguage extends Model
{
    public $timestamps = false;
    protected $fillable = ['album_id', 'language_id', 'title', 'description'];
}
