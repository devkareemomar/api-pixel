<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'username',
        'facebook',
        'twitter',
        'google',
        'apple',
        'language_id',
        'currency_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function username(): Attribute
    {
        return Attribute::make(
            set: fn(?string $value, array $attributes) => $value ? $value : $attributes['email'],
        );
    }
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class,'language_id');
    }

    public function socialiteProviders(): HasMany
    {
        return $this->hasMany(SocialiteProvider::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
