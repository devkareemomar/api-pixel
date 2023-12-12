<?php

namespace App\Models;

use App\Traits\Filter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use Filter;
    protected $fillable = [
        'name',
    ];

    public function items()
    {
        return $this->hasMany(MenuItem::class, 'menu_id')->where('parent_id', 0)->orderBy('sort', 'ASC');
    }
}
