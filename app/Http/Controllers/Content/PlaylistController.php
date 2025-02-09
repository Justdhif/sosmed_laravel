<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Playlist;
use App\Models\PlaylistItem;
use Illuminate\Support\Facades\Auth;

class PlaylistController extends Controller
{
    /**
     * Menampilkan daftar playlist user.
     */
    public function index()
    {
        $playlists = Auth::user()->playlists;
        return view('playlists.index', compact('playlists'));
    }

    /**
     * Menampilkan form untuk membuat playlist baru.
     */
    public function create()
    {
        return view('playlists.create');
    }

    /**
     * Menyimpan playlist baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $playlist = new Playlist();
        $playlist->user_id = Auth::id();
        $playlist->name = $request->name ?? 'Playlist Saya'; // Default nama
        $playlist->description = $request->description;
        $playlist->image = null; // Akan di-set saat menambahkan video pertama
        $playlist->save();

        return redirect()->route('playlist.index')->with('success', 'Playlist berhasil dibuat.');
    }

    /**
     * Menampilkan detail playlist dan video yang ada di dalamnya.
     */
    public function show($id)
    {
        $playlist = Playlist::with('items')->findOrFail($id);
        return view('playlists.show', compact('playlist'));
    }

    public function edit(Playlist $playlist)
    {
        if (Auth::id() !== $playlist->user_id) {
            return redirect()->route('playlists.index')->with('error', 'Anda tidak memiliki izin untuk mengedit playlist ini.');
        }

        return view('playlists.edit', compact('playlist'));
    }

    public function update(Request $request, Playlist $playlist)
    {
        if (Auth::id() !== $playlist->user_id) {
            return redirect()->route('playlists.index')->with('error', 'Anda tidak memiliki izin untuk mengedit playlist ini.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            // 'thumbnail' => 'nullable|string|url',
        ]);

        // Update data
        $playlist->name = $request->name;
        $playlist->description = $request->description;
        // $playlist->thumbnail = $request->thumbnail;
        $playlist->save();

        return redirect()->route('playlist.show', $playlist->id)->with('success', 'Playlist berhasil diperbarui.');
    }

    public function destroy(Playlist $playlist)
    {
        if (Auth::id() !== $playlist->user_id) {
            return redirect()->route('playlists.index')->with('error', 'Anda tidak memiliki izin untuk menghapus playlist ini.');
        }

        $playlist->delete();

        return redirect()->route('playlist.index')->with('success', 'Playlist berhasil dihapus.');
    }

    /**
     * Menambahkan video ke dalam playlist.
     */
    public function addVideo(Request $request, $id)
    {
        $request->validate([
            'video_url' => 'required|url',
        ]);

        $playlist = Playlist::findOrFail($id);

        // Tambahkan video ke dalam playlist
        $item = new PlaylistItem();
        $item->playlist_id = $playlist->id;
        $item->video_url = $request->video_url;
        $item->save();

        // Jika ini adalah video pertama dalam playlist, set sebagai thumbnail
        if (!$playlist->image) {
            $playlist->image = $this->getYouTubeThumbnail($request->video_url);
            $playlist->save();
        }

        return redirect()->route('playlist.show', $id)->with('success', 'Video berhasil ditambahkan.');
    }

    public function removeAllVideos(Playlist $playlist)
    {
        // Pastikan hanya pemilik playlist yang bisa menghapus
        if (auth()->id() !== $playlist->user_id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke playlist ini.');
        }

        $playlist->videos()->detach(); // Hapus semua relasi video dari playlist

        return redirect()->back()->with('success', 'Semua video berhasil dihapus dari playlist.');
    }

    public function removeSelectedVideos(Request $request, Playlist $playlist)
    {
        $request->validate([
            'videos' => 'required|array',
        ]);

        $playlist->videos()->whereIn('id', $request->videos)->delete();

        return redirect()->back()->with('success', 'Video yang dipilih telah dihapus.');
    }

    /**
     * Fungsi untuk mendapatkan thumbnail dari video YouTube.
     */
    private function getYouTubeThumbnail($url)
    {
        preg_match('/(?:youtu\.be\/|youtube\.com\/(?:.*v=|.*\/v\/|.*embed\/|.*watch\?v=))([^&?]+)/', $url, $matches);
        return isset($matches[1]) ? "https://img.youtube.com/vi/{$matches[1]}/hqdefault.jpg" : null;
    }
}

