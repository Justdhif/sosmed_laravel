<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Models\Post;
use App\Models\Media;
use App\Models\Comment;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Menampilkan form untuk membuat postingan baru.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Menampilkan detail postingan beserta komentar dan postingan lain.
     */
    public function show($id)
    {
        $post = Post::with(['user', 'likes', 'comments.user', 'media'])->findOrFail($id);
        $comments = $post->comments()->latest()->get();
        $otherPosts = Post::with(['user', 'media'])->where('id', '!=', $id)->latest()->paginate(5);

        return view('posts.show', compact('post', 'otherPosts', 'comments'));
    }

    /**
     * Menyimpan postingan baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'media.*' => 'required|file|mimes:jpg,jpeg,png|max:10240', // Hanya gambar
        ]);

        // Simpan postingan
        $post = Post::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => Auth::id(),
        ]);

        // Simpan media jika ada
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $path = $file->store('uploads', 'public');
                Media::create([
                    'post_id' => $post->id,
                    'path' => $path,
                    'type' => 'image',
                ]);
            }
        }

        return redirect()->route('home')->with('success', 'Post berhasil dibuat!');
    }

    /**
     * Menampilkan form untuk mengedit postingan.
     */
    public function edit(Post $post)
    {
        $this->authorizeUser($post);

        return view('posts.edit', compact('post'));
    }

    /**
     * Memperbarui postingan yang ada.
     */
    public function update(Request $request, Post $post)
    {
        $this->authorizeUser($post);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        // Update data
        $post->title = $request->title;
        $post->description = $request->description;

        // Jika ada gambar baru, simpan dan hapus yang lama
        if ($request->hasFile('image')) {
            Storage::delete('public/' . $post->image_path);
            $post->image_path = $request->file('image')->store('posts', 'public');
        }

        $post->save();

        return redirect()->route('posts.show', $post->id)->with('success', 'Postingan berhasil diperbarui.');
    }

    /**
     * Menghapus postingan.
     */
    public function destroy(Post $post)
    {
        $this->authorizeUser($post);

        Storage::delete('public/' . $post->image_path);
        $post->delete();

        return redirect()->route('home')->with('success', 'Postingan berhasil dihapus.');
    }

    /**
     * Menampilkan postingan secara acak.
     */
    public function explore()
    {
        $posts = Post::inRandomOrder()->limit(20)->get(); // Ambil 20 postingan secara acak
        return view('posts.explore', compact('posts'));
    }

    /**
     * Memberi like atau unlike pada postingan.
     */
    public function likePost($postId)
    {
        $post = Post::findOrFail($postId);
        $user = auth()->user();

        // Cek apakah user sudah memberi like
        $like = Like::where('user_id', $user->id)->where('post_id', $post->id)->first();

        if ($like) {
            // Jika sudah like, maka unlike
            $like->delete();
        } else {
            // Jika belum like, maka like
            Like::create([
                'user_id' => $user->id,
                'post_id' => $post->id,
            ]);

            Notification::create([
                'user_id' => $post->user_id, // Pemilik postingan
                'action_user_id' => auth()->id(), // User yang melakukan like
                'type' => 'like',
                'post_id' => $post->id,
            ]);
        }

        return back();  // Redirect kembali ke halaman yang sama
    }

    /**
     * Mengomentari postingan.
     */
    public function commentOnPost(Request $request, $postId)
    {
        $request->validate([
            'comment' => 'required|string',
        ]);

        $post = Post::findOrFail($postId);
        $user = auth()->user();

        $comment = new Comment();
        $comment->user_id = $user->id;
        $comment->post_id = $post->id;
        $comment->comment = $request->comment;
        $comment->save();

        Notification::create([
            'user_id' => $post->user_id, // Pemilik postingan
            'action_user_id' => auth()->id(), // User yang melakukan komentar
            'type' => 'comment',
            'post_id' => $post->id,
        ]);

        return redirect()->back();  // Redirect kembali ke halaman yang sama
    }

    /**
     * Memeriksa apakah pengguna memiliki izin untuk mengedit atau menghapus postingan.
     */
    protected function authorizeUser(Post $post)
    {
        if (Auth::id() !== $post->user_id) {
            return redirect()->route('posts.index')->with('error', 'Anda tidak memiliki izin untuk mengakses postingan ini.');
        }
    }
}
