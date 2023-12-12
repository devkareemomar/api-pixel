<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WidgetCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'thumbnail', 'folder_name'];

    public function templates()
    {
        return $this->hasMany(WidgetTemplate::class);
    }
}
