<?php

namespace App\Models;

use App\Traits\Sort;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasFactory;
    use Sort;

    protected $fillable = ['code', 'project_id', 'url', 'platform'];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

}
