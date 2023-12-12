<?php

namespace App\Models;

use App\Traits\Sort;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormBuilderData extends Model
{
    use HasFactory, SoftDeletes;
    use Sort;
    protected $fillable=[
        'form_builder_id','price','checks_date','status','national_id','data'
    ];
}
