<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Notification;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman utama dengan postingan dan notifikasi.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Mengambil semua postingan dengan informasi pengguna
        $posts = Post::with('user')->orderBy('created_at', 'desc')->get();

        // Mengambil notifikasi untuk pengguna yang sedang login
        $notifications = Notification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        // Mengirim data postingan dan notifikasi ke view
        return view('home', compact('posts', 'notifications'));
    }
}
