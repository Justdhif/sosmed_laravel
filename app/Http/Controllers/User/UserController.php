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
    public function searchUsers(Request $request)
    {
        $searchTerm = $request->input('search_term');
        $users = User::where('name', 'like', '%' . $searchTerm . '%')->get();

        return response()->json($users);
    }

    public function show($name)
    {
        $user = User::where('name', $name)->firstOrFail();
        $posts = Post::with('media')->where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        $likedPosts = $user->likedPosts()->with('media')->orderBy('created_at', 'desc')->get();
        $followingUsers = auth()->user()->following;
        $shopProfile = ShopProfile::where('user_id', $user->id)
            ->with([
                'products' => function ($query) {
                    $query->orderBy('created_at', 'desc');
                }
            ])
            ->first();
        $playlists = Playlist::where('user_id', $user->id)->get();
        // Jika user tidak memiliki profil toko, set nilai default
        $productCount = $shopProfile ? $shopProfile->products->count() : 0;
        $averageRating = $shopProfile && $shopProfile->products->count() > 0
            ? $shopProfile->products->avg('rating')
            : 0;

        return view('profile', compact('user', 'posts', 'likedPosts', 'followingUsers', 'shopProfile', 'playlists', 'productCount', 'averageRating'));
    }
}

