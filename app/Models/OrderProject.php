<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderProject extends Model
{
    use HasFactory;


    protected $fillable = ['order_id', 'project_id', 'qty', 'price', 'tax_amount', 'created_at', 'updated_at'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
