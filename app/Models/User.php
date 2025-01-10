<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'gender',
        'fields_of_work',
        'linkedin_url',
        'mobile_number',
        'wallet_balance',
        'visible',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'fields_of_work' => 'array',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function wishlist()
    {
        return $this->hasMany(Wishlist::class, 'user_id');
    }
}
