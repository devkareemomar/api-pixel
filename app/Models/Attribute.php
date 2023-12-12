<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'input_type_id', 'min_quantity', 'max_quantity', 'desktop_visibility', 'tablet_visibility', 'mobile_visibility', 'has_order'];

    public function inputType()
    {
        return $this->belongsTo(InputType::class);
    }

}
