<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'total_amount',
        'client_notes',
        'admin_notes',
        'is_paid',
    ];

    public function cartProjects(): HasMany
    {
        return $this->hasMany(CartProject::class);
    }
}
