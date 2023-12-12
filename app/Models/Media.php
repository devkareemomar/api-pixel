<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    public $timestamps = false;

    protected $fillable = ['video', 'image'];
}
