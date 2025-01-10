<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $genderFilter = $request->query('gender');
        $fieldOfWorkFilter = $request->query('field_of_work');

        // Dapatkan ID pengguna yang ada di wishlist
        $user = auth()->user();
        $wishlistUserIds = $user->wishlist()->pluck('target_user_id');

        // Ambil data pengguna berdasarkan filter dan exclude wishlist
        // Ambil data pengguna berdasarkan filter dan exclude wishlist


        $users = User::query()

        ->whereNotNull('fields_of_work')
        ->where('id', '!=', $user->id) // Mengecualikan user yang sedang login
        ->whereNotIn('id', $wishlistUserIds) // Exclude user dari wishlist yang sedang dalam status 'pending'
        ->whereNotIn('id', function ($query) use ($user) {
            $query->select('user_id')
                ->from('wishlists')
                ->where('target_user_id', $user->id)
                ->where('status', 'pending');
        }) // Mengecualikan user yang sudah mengirim request 'pending' ke user yang sedang login
        ->whereNotIn('id', function ($query) use ($user) {
            $query->select('target_user_id') // Mengambil target_user_id yang statusnya accepted
                ->from('wishlists')
                ->where('user_id', $user->id) // Menyesuaikan dengan user yang login
                ->where('status', 'accepted');
        }) // Mengecualikan user yang sudah di-accept oleh user yang sedang login
        ->whereNotIn('id', function ($query) use ($user) {
            $query->select('user_id') // Mengambil user_id yang statusnya accepted
                ->from('wishlists')
                ->where('target_user_id', $user->id) // Menyesuaikan dengan user yang login
                ->where('status', 'accepted');
        }) // Mengecualikan user yang sudah menerima request dengan status accepted
        ->when($genderFilter, function ($query, $genderFilter) {
            return $query->where('gender', $genderFilter);
        })
        ->when($fieldOfWorkFilter, function ($query, $fieldOfWorkFilter) {
            return $query->where('fields_of_work', 'like', '%' . $fieldOfWorkFilter . '%');
        })
        ->get();



        return view('dashboard', compact('users', 'genderFilter', 'fieldOfWorkFilter'));
    }

    public function addToWishlist($targetUserId)
    {
        $user = auth()->user();

        // Pastikan user tidak menambahkan diri sendiri ke wishlist
        if ($user->id == $targetUserId) {
            return back()->with('message', 'You cannot add yourself to the wishlist!');
        }

        // Periksa apakah permintaan sudah ada
        $existing = Wishlist::where('user_id', $user->id)
                            ->where('target_user_id', $targetUserId)
                            ->first();

        if (!$existing) {
            // Menambahkan ke wishlist dengan status 'pending'
            Wishlist::create([
                'user_id' => $user->id,
                'target_user_id' => $targetUserId,
                'status' => 'pending',
            ]);

            return redirect()->route('wishlist.index')->with('message', 'Your request has been sent!');
        }

        return back()->with('message', 'You have already sent a request to this user.');
    }
}
