<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(User $user)
    {
        $authUser = auth()->user();

        // Ambil pesan antara pengguna login dan target
        $messages = Message::where(function ($query) use ($authUser, $user) {
            $query->where('sender_id', $authUser->id)
                  ->where('receiver_id', $user->id);
        })->orWhere(function ($query) use ($authUser, $user) {
            $query->where('sender_id', $user->id)
                  ->where('receiver_id', $authUser->id);
        })->orderBy('created_at')->get();

        return view('chat.index', compact('messages', 'user'));
    }

    public function store(Request $request, User $user)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $user->id,
            'message' => $request->message,
        ]);

        return redirect()->route('chat.index', $user->id);
    }
}
