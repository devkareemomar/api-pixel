<?php

namespace App\Models;

use App\Traits\Sort;
use App\Traits\Visitable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use SoftDeletes, HasFactory;
    use Sort;
    use Visitable;

    protected $fillable = ['project_id', 'name', 'title', 'description', 'metadata'];
}
