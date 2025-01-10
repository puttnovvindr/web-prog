<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;


class Wishlist extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'target_user_id', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // User yang menerima permintaan
    public function targetUser()
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }
}
