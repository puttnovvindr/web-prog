<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ThumbButtonController extends Controller
{
    public function addToWishlist($targetUserId)
    {
        $user = auth()->user();

        // Cek apakah pengguna sudah menambahkan target user ke wishlist
        $existing = Wishlist::where('user_id', $user->id)
                            ->where('target_user_id', $targetUserId)
                            ->first();

        if (!$existing) {
            // Menambahkan pengguna ke wishlist
            Wishlist::create([
                'user_id' => $user->id,
                'target_user_id' => $targetUserId,
            ]);

            return back()->with('message', 'User added to wishlist!');
        }

        return back()->with('message', 'User already in your wishlist.');
    }

}
