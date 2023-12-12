<?php

namespace App\Models;

use App\Traits\Sort;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormLanguage extends Model
{
    use HasFactory, SoftDeletes;
    use Sort;

    protected $fillable = [
        'form_builder_id', 'language_id', 'status_name', 'form_data'
    ];
}
