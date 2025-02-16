<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Message;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        // Ambil daftar akun yang diikuti oleh pengguna
        $followingUsers = User::whereIn('id', function ($query) use ($userId) {
            $query->select('followed_id')->from('follows')->where('follower_id', $userId);
        })->get();

        // Ambil daftar percakapan unik
        $conversations = Message::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->with(['sender', 'receiver'])
            ->latest()
            ->get()
            ->groupBy(function ($message) use ($userId) {
                // Kelompokkan percakapan berdasarkan user yang berinteraksi dengan pengguna
                return $message->sender_id == $userId ? $message->receiver_id : $message->sender_id;
            });

        // Hitung pesan yang belum dibaca
        $unreadCounts = Message::where('receiver_id', $userId)
            ->where('is_read', false)
            ->selectRaw('sender_id, COUNT(*) as unread_count')
            ->groupBy('sender_id')
            ->pluck('unread_count', 'sender_id');

        // Jika ada pencarian user
        $query = $request->input('query');
        $users = $query ? User::where('name', 'LIKE', "%$query%")
            ->where('id', '!=', $userId)
            ->limit(10)
            ->get() : [];

        // Cek apakah ada user yang diklik untuk chat
        $selectedUser = $request->has('user') ? User::find($request->user) : null;
        $messages = collect();

        if ($selectedUser) {
            // Ambil percakapan antara pengguna dan user yang dipilih
            $messages = Message::where(function ($q) use ($userId, $selectedUser) {
                $q->where('sender_id', $userId)->where('receiver_id', $selectedUser->id);
            })->orWhere(function ($q) use ($userId, $selectedUser) {
                $q->where('sender_id', $selectedUser->id)->where('receiver_id', $userId);
            })->orderBy('created_at', 'asc')->get();

            // Tandai pesan sebagai terbaca
            Message::where('sender_id', $selectedUser->id)
                ->where('receiver_id', $userId)
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }



        return view('messages.index', compact(
            'followingUsers',
            'conversations',
            'unreadCounts',
            'users',
            'query',
            'selectedUser',
            'messages'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000',
        ]);

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
            'is_read' => false,
        ]);

        return redirect()->route('messages.index', ['user' => $request->receiver_id]);
    }
}
