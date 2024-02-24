<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderProject extends Model
{
    use HasFactory;


    protected $fillable = ['order_id', 'project_id', 'qty', 'price', 'tax_amount',
    'recurring',
    'recurring_type',
    'recurring_start_date',
    'recurring_end_date',
    'created_at', 'updated_at','name','email','phone','comment'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
