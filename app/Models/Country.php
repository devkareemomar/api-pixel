<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'short_name', 'language_id', 'currency_id', 'flag'];

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    protected $hidden = ['pivot'];

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
