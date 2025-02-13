<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\Category;
use App\Models\CommentReply;
use App\Models\Notification;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman utama dengan postingan dan notifikasi.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Ambil kategori dari query string
        $categoryId = $request->query('category', 'all');

        // Ambil semua kategori untuk ditampilkan di tab kategori
        $categories = Category::all();
        // Query untuk video (tanpa pagination)
        $videosQuery = Post::whereNotNull('youtube_video_id')->orderBy('created_at', 'desc');

        // Query untuk gambar (dengan pagination)
        $imagesQuery = Post::whereNotNull('image_path')->orderBy('created_at', 'desc');

        // Jika kategori dipilih, filter berdasarkan kategori tersebut
        if ($categoryId !== 'all') {
            $videosQuery->where('category_id', $categoryId);
            $imagesQuery->where('category_id', $categoryId);
        }

        // Ambil hasil query
        $videos = $videosQuery->get();
        $images = $imagesQuery->paginate(4); // 4 gambar per halaman

        // Mengambil notifikasi untuk pengguna yang sedang login
        $notifications = Notification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        // Mengirim data postingan dan notifikasi ke view
        return view('home', compact('images', 'videos', 'notifications', 'categories'));
    }
}
