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
        // Ambil parameter kategori dan pencarian dari query string
        $categoryId = $request->query('category', 'all');
        $searchQuery = $request->query('query', '');

        // Ambil semua kategori untuk tab kategori
        $categories = Category::all();

        // Query untuk video (tanpa pagination)
        $videosQuery = Post::whereNotNull('youtube_video_id')->orderBy('created_at', 'desc');

        // Query untuk gambar (dengan pagination)
        $imagesQuery = Post::whereNotNull('image_path')->orderBy('created_at', 'desc');

        // Jika kategori dipilih (kecuali "all"), filter berdasarkan kategori
        if ($categoryId !== 'all' && $categoryId !== 'foto' && $categoryId !== 'video') {
            $videosQuery->where('category_id', $categoryId);
            $imagesQuery->where('category_id', $categoryId);
        }

        // Jika kategori "foto" dipilih, hanya ambil gambar
        if ($categoryId === 'foto') {
            $videosQuery->whereRaw('1 = 0'); // Trik agar tidak mengambil video
        }

        // Jika kategori "video" dipilih, hanya ambil video
        if ($categoryId === 'video') {
            $imagesQuery->whereRaw('1 = 0'); // Trik agar tidak mengambil gambar
        }

        // Jika ada pencarian, filter berdasarkan judul atau deskripsi
        if (!empty($searchQuery)) {
            $videosQuery->where(function ($query) use ($searchQuery) {
                $query->where('title', 'like', "%$searchQuery%")
                    ->orWhere('description', 'like', "%$searchQuery%");
            });

            $imagesQuery->where(function ($query) use ($searchQuery) {
                $query->where('title', 'like', "%$searchQuery%")
                    ->orWhere('description', 'like', "%$searchQuery%");
            });
        }

        // Ambil hasil query
        $videos = $videosQuery->get();
        $images = $imagesQuery->paginate(4); // 4 gambar per halaman

        // Mengambil notifikasi untuk pengguna yang sedang login
        $notifications = Notification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('home', compact('images', 'videos', 'notifications', 'categories'));
    }
}
