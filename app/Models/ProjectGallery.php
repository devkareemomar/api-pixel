<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ProjectGallery extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'project_gallery';
    protected $fillable = [
        'name', 'project_id', 'path', 'status', 'order', 'type'
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
        'project_id',
        'id',
        'type',
        'status'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }


}
