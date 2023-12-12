<?php

namespace App\Models;

use App\Traits\Filter;
use App\Traits\Sort;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectStatus extends Model
{
    use Filter;
    use HasFactory;
    use Sort;
    use SoftDeletes;

    protected $fillable = [
        'name', 'description', 'color', 'is_new', 'is_completed', 'is_active'
    ];

    public function getUniqueColumns()
    {
        return ['name'];
    }
}
