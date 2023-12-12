<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SocialiteProvider extends Model
{
    use HasFactory;

    protected $table = 'socialite_providers';

    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'provider',
        'provider_user_id',
        'access_token',
        'refresh_token',
        'avatar',
    ];

    protected $hidden = [
        'access_token',
        'refresh_token',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
