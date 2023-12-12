<?php

namespace App\Models;

use App\Traits\Sort;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormBuilder extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Sort;

    protected $fillable = [
        'project_id', 'status_name', 'active', 'form_data', 'locale'
    ];
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    public function formLanguage()
    {
        return $this->hasMany(FormLanguage::class);
    }
    public function formData()
    {
        return $this->hasMany(FormBuilderData::class);
    }
}
