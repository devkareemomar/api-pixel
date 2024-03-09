<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PeriodicallyDonate extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'user_id',
        'payment_type',
        'recurring',
        'recurring_type',
        'recurring_start_date',
        'recurring_end_date',
        'amount',
    ];



    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
