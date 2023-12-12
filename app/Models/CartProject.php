<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartProject extends Model
{

    protected $table = 'cart_project';

    protected $fillable = [
        'cart_id',
        'project_id',
        'amount',
        'gifted_to_email',
        'gifted_to_phone',
        'gifted_to_name',
        'recurring',
        'donor_comment',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

}
