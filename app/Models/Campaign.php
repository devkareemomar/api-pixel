<?php

namespace App\Models;

use App\Traits\Sort;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends Model
{
    use HasFactory, SoftDeletes;
    use Sort;

    protected $fillable = [
        'title',
        'image',
        'description',
        'slogan',
        'start_date',
        'end_date',
        'is_active',
        'is_home_slider'];

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'campaign_projects');
    }
}
