<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use App\Models\Playlist;
use App\Models\ShopProfile;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Mencari pengguna berdasarkan nama.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchUsers(Request $request)
    {
        $searchTerm = $request->input('search_term');
        $users = User::where('name', 'like', '%' . $searchTerm . '%')->get();

        return response()->json($users);
    }

    /**
     * Menampilkan profil pengguna.
     *
     * @param string $name
     * @return \Illuminate\View\View
     */
    public function show($name)
    {
        $user = User::where('name', $name)->firstOrFail();

        // Mengambil data postingan pengguna
        $posts = Post::with('media')->where('user_id', $user->id)->orderBy('created_at', 'desc')->get();

        // Mengambil postingan yang disukai oleh pengguna
        $likedPosts = $user->likedPosts()->with('media')->orderBy('created_at', 'desc')->get();

        // Mengambil pengguna yang diikuti
        $followingUsers = auth()->user()->following;

        // Mengambil profil toko pengguna
        $shopProfile = ShopProfile::where('user_id', $user->id)
            ->with(['products' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->first();

        // Mengambil daftar playlist pengguna
        $playlists = Playlist::where('user_id', $user->id)->get();

        // Menghitung jumlah produk dan rata-rata rating
        $productCount = $shopProfile ? $shopProfile->products->count() : 0;
        $averageRating = $shopProfile && $productCount > 0
            ? $shopProfile->products->avg('rating')
            : 0;

        return view('profile', compact(
            'user',
            'posts',
            'likedPosts',
            'followingUsers',
            'shopProfile',
            'playlists',
            'productCount',
            'averageRating'
        ));
    }
}
