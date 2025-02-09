<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // Tandai notifikasi sebagai dibaca
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        if ($notification->user_id === auth()->id()) {
            $notification->update(['is_read' => true]);
        }
        return back();
    }
}

