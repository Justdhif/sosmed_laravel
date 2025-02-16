<?php

namespace App\Http\Controllers\Content;

use App\Models\Category;
use App\Models\Like;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function create()
    {
        $categories = Category::all();
        return view('posts.create', compact('categories'));
    }

    public function show($id)
    {
        $post = Post::with(['user', 'likes', 'comments.user'])->findOrFail($id);
        $comments = $post->comments()->latest()->get();
        $otherPosts = Post::with(['user'])->where('id', '!=', $id)->latest()->paginate(5);

        return view('posts.show_post', compact('post', 'otherPosts', 'comments'));
    }

    public function storeImage(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $imagePath = $request->file('image')->store('images', 'public');

        Post::create([
            'title' => $request->title,
            'description' => $request->description,
            'image_path' => $imagePath,
            'youtube_video_id' => null,
            'user_id' => Auth::id()
        ]);

        return redirect()->back()->with('success', 'Post dengan gambar berhasil dibuat!');
    }

    // Simpan Post dengan Video YouTube
    public function storeVideo(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'youtube_video_id' => 'required'
        ]);

        // Ambil hanya ID dari URL (jika user memasukkan URL lengkap)
        preg_match(
            '/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]+)/',
            $request->youtube_video_id,
            $matches
        );
        $videoId = $matches[1] ?? $request->youtube_video_id;

        Post::create([
            'title' => $request->title,
            'description' => $request->description,
            'image_path' => null, // Kosongkan gambar
            'youtube_video_id' => $videoId,
            'user_id' => Auth::id()
        ]);

        return redirect()->back()->with('success', 'Post dengan video berhasil dibuat!');
    }

    public function edit(Post $post)
    {
        $this->authorizeUser($post);
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $this->authorizeUser($post);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
            'youtube_video_id' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('image')) {
            Storage::delete('public/' . $post->image_path);
            $post->image_path = $request->file('image')->store('uploads', 'public');
        }

        $post->title = $request->title;
        $post->description = $request->description;
        $post->youtube_video_id = $request->youtube_video_id;
        $post->save();

        return redirect()->route('posts.show', $post->id)->with('success', 'Postingan berhasil diperbarui.');
    }

    public function destroy(Post $post)
    {
        $this->authorizeUser($post);

        if ($post->image_path) {
            Storage::delete('public/' . $post->image_path);
        }
        $post->delete();

        return redirect()->route('home')->with('success', 'Postingan berhasil dihapus.');
    }

    public function likePost($postId)
    {
        $post = Post::findOrFail($postId);
        $user = Auth::user();
        $like = Like::where('post_id', $post->id)
            ->where('user_id', $user->id)
            ->first();

        if ($like) {
            $like->delete();
            $notification = Notification::where('post_id', $post->id)
                ->where('user_id', $user->id)
                ->first();
            if ($notification) {
                $notification->delete();
            }
        } else {
            Like::create([
                'post_id' => $post->id,
                'user_id' => $user->id,
            ]);
            $notification = Notification::create([
                'post_id' => $post->id,
                'user_id' => $user->id,
                'type' => 'like',
                'action_user_id' => $user->id,
            ]);
        }

        return redirect()->back();
    }

    public function commentOnPost(Request $request, $postId)
    {
        $request->validate([
            'comment' => 'required|string',
        ]);

        $post = Post::findOrFail($postId);
        $user = auth()->user();

        Comment::create([
            'post_id' => $post->id,
            'user_id' => $user->id,
            'comment' => $request->comment,
        ]);

        Notification::create([
            'user_id' => $post->user_id, // Pemilik postingan
            'action_user_id' => auth()->id(), // User yang melakukan komentar
            'type' => 'comment',
            'post_id' => $post->id,
        ]);

        return redirect()->back();  // Redirect kembali ke halaman yang sama
    }

    public function replyToComment(Request $request, $commentId)
    {
        $request->validate([
            'reply' => 'required|string',
        ]);

        $comment = Comment::findOrFail($commentId);

        $comment->replies()->create([
            'user_id' => auth()->id(),
            'comment_id' => $commentId,
            'reply' => $request->reply,
        ]);

        return redirect()->route('posts.show', $comment->post_id)->with('success', 'Reply added successfully!');
    }

    public function searchVideos(Request $request)
    {
        $query = $request->input('query');
        $category = $request->input('category', 'all');

        $videos = Post::whereNotNull('youtube_video_id')
            ->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%$query%")
                    ->orWhere('description', 'LIKE', "%$query%");
            })
            ->when($category !== 'all', function ($q) use ($category) {
                return $q->where('category_id', $category); // Filter berdasarkan kategori jika bukan 'all'
            })
            ->inRandomOrder()
            ->get();

        $categories = Category::all();

        return view('posts.search_videos', compact('videos', 'query', 'categories'));
    }

    public function searchImages(Request $request)
    {
        $query = $request->input('query');
        $category = $request->input('category', 'all');

        $images = Post::whereNotNull('image_path')
            ->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%$query%")
                    ->orWhere('description', 'LIKE', "%$query%");
            })
            ->when($category !== 'all', function ($q) use ($category) {
                return $q->where('category_id', $category); // Filter berdasarkan kategori jika bukan 'all'
            })
            ->inRandomOrder()
            ->get();

        $categories = Category::all();

        return view('posts.search_images', compact('images', 'query', 'categories'));
    }

    protected function authorizeUser(Post $post)
    {
        if (Auth::id() !== $post->user_id) {
            return redirect()->route('posts.index')->with('error', 'Anda tidak memiliki izin.');
        }
    }
}
