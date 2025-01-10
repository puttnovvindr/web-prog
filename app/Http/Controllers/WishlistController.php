<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    // Controller method
    public function index()
    {
        $user = auth()->user();

        // Ambil daftar pending requests
        $pendingRequests = Wishlist::where('target_user_id', auth()->id())
            ->where('status', 'pending')
            ->with('user') // user adalah relasi ke model User dari user_id
            ->get();

        // Ambil wishlist yang dimiliki oleh user, kecuali yang ada di pending requests
        $wishlistItems = Wishlist::where('user_id', auth()->id())
            ->whereNotIn('target_user_id', $pendingRequests->pluck('user_id')) // Memastikan user yang ada di pending request tidak ada di wishlist
            ->with('targetUser') // targetUser adalah relasi ke model User dari target_user_id
            ->get();

        // Ambil semua wishlist yang statusnya 'accepted' dan terkait dengan user yang login
        $acceptedRequests = Wishlist::where(function($query) use ($user) {
            $query->where('user_id', $user->id)
                ->orWhere('target_user_id', $user->id);
        })
        ->where('status', 'accepted')
        ->get();

        return view('wishlist.index', compact('pendingRequests', 'acceptedRequests', 'wishlistItems'));
    }


    public function sendRequest($targetUserId)
    {
        $user = auth()->user(); // Pengguna yang mengirim request
        $targetUser = User::findOrFail($targetUserId); // Pengguna yang menerima request

        // Cek apakah request sudah pernah dikirim
        $existingRequest = Wishlist::where('user_id', $user->id)
                                ->where('target_user_id', $targetUser->id)
                                ->first();

        if ($existingRequest) {
            return redirect()->back()->with('error', 'Request sudah dikirim sebelumnya.');
        }

        // Membuat request baru
        Wishlist::create([
            'user_id' => $user->id,
            'target_user_id' => $targetUser->id,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Request berhasil dikirim.');
    }


    public function acceptRequest($requestId)
    {
        $user = auth()->user(); // Pengguna yang menerima request
        $wishlist = Wishlist::findOrFail($requestId);

        // Cek apakah request untuk user ini
        if ($wishlist->target_user_id != $user->id) {
            return redirect()->back()->with('error', 'Bukan request kamu.');
        }

        // Ubah status menjadi accepted
        $wishlist->update([
            'status' => 'accepted',
        ]);

        return redirect()->route('wishlist.index')->with('success', 'Request berhasil diterima.');
    }


    public function remove($wishlistId)
    {
        $user = auth()->user();

        // Cari wishlist yang sesuai dengan ID user dan target_user_id
        $wishlist = Wishlist::where('user_id', $user->id)
                            ->where('id', $wishlistId)
                            ->first();

        if ($wishlist) {
            $wishlist->delete();
            return redirect()->route('dashboard')->with('message', 'Removed from Wishlist');
        }

        return back()->with('message', 'User not found in your wishlist.');
    }

    // app/Http/Controllers/WishlistController.php

    public function showAcceptedRequestsForUserA()
    {
        $user = auth()->user(); // User A

        // Menampilkan request yang diterima oleh A
        $acceptedRequests = Wishlist::where('user_id', $user->id)
                                    ->where('status', 'accepted')
                                    ->get();

        return view('wishlist.index', compact('acceptedRequests'));
    }


    public function showAcceptedRequestsForUserB()
    {
        $user = auth()->user(); // User B

        // Menampilkan request yang diterima oleh B
        $acceptedRequests = Wishlist::where('target_user_id', $user->id)
                                    ->where('status', 'accepted')
                                    ->get();

        return view('wishlist.index', compact('acceptedRequests'));
    }

    public function declineRequest($requestId)
    {
        $request = Wishlist::find($requestId);
        $request->status = 'declined'; // Update status jadi declined
        $request->save();

        return redirect()->route('wishlist.index')->with('message', 'Request declined!');
    }

}
