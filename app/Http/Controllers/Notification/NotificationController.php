<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Tandai notifikasi sebagai dibaca.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAsRead($id)
    {
        // Temukan notifikasi berdasarkan ID
        $notification = Notification::findOrFail($id);

        // Cek apakah notifikasi milik pengguna yang sedang login
        if ($notification->user_id === auth()->id()) {
            $notification->update(['is_read' => true]);
        }

        return back()->with('success', 'Notifikasi berhasil ditandai sebagai dibaca.');
    }
}
