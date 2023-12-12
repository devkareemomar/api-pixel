<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    protected $fillable = ['title', 'description', 'is_active'];

    public function album_language()
    {
        return $this->hasMany(AlbumLanguage::class);
    }

    protected $hidden = ['pivot'];

    public function media()
    {
        return $this->belongsToMany(Media::class, 'album_media');
    }
}
