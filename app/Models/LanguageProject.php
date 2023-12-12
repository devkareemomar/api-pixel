<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LanguageProject extends Model
{

    protected $table = 'language_project';

    protected $guarded = ['id'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
