<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use App\Models\Playlist;
use Illuminate\Http\Request;
use App\Models\PlaylistVideo;

class YoutubeController extends Controller
{
    private $apiKey;
    private $client;

    public function __construct()
    {
        $this->apiKey = env('YOUTUBE_API_KEY'); // Ambil API Key dari .env
        $this->client = new Client();
    }

    /**
     * Menampilkan halaman home dengan video default atau hasil pencarian.
     */
    public function index(Request $request)
    {
        $query = $request->input('query');

        // Jika ada query pencarian, tampilkan hasil pencarian
        return $query ? $this->search($query) : $this->showDefaultVideos();
    }

    /**
     * Menampilkan video default.
     */
    private function showDefaultVideos()
    {
        try {
            // Ambil video terbaru dari channel tertentu
            $channelId = 'UCoIiiHof6BJ85PLuLkuxuhw'; // Ganti dengan ID channel yang diinginkan
            $response = $this->client->get('https://www.googleapis.com/youtube/v3/search', [
                'query' => [
                    'part' => 'snippet',
                    'channelId' => $channelId,
                    'maxResults' => 12, // Jumlah video yang ditampilkan
                    'order' => 'date', // Urutkan berdasarkan tanggal upload terbaru
                    'type' => 'video', // Hanya ambil video
                    'key' => $this->apiKey,
                ]
            ]);

            $data = json_decode($response->getBody(), true);

            // Format data video
            $videos = array_map(function ($item) {
                return [
                    'id' => $item['id']['videoId'],
                    'snippet' => $item['snippet'],
                ];
            }, $data['items']);

            // Kirim data ke view
            return view('youtube.index', ['videos' => $videos]);
        } catch (\Exception $e) {
            return redirect()->route('youtube.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menangani pencarian video.
     */
    private function search($query)
    {
        try {
            // Ambil hasil pencarian dari YouTube API
            $response = $this->client->get('https://www.googleapis.com/youtube/v3/search', [
                'query' => [
                    'part' => 'snippet',
                    'q' => $query,
                    'type' => 'video',
                    'maxResults' => 12, // Jumlah hasil yang ditampilkan
                    'key' => $this->apiKey,
                ]
            ]);

            $data = json_decode($response->getBody(), true);

            // Format data video
            $videos = array_map(function ($item) {
                return [
                    'id' => $item['id']['videoId'],
                    'snippet' => $item['snippet'],
                ];
            }, $data['items']);

            // Kirim hasil pencarian ke view
            return view('youtube.index', ['videos' => $videos, 'query' => $query]);
        } catch (\Exception $e) {
            return redirect()->route('youtube.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan detail video.
     */
    public function showVideoDetail($videoId)
    {
        try {
            // Ambil detail video dari YouTube API
            $response = $this->client->get('https://www.googleapis.com/youtube/v3/videos', [
                'query' => [
                    'part' => 'snippet,contentDetails,statistics',
                    'id' => $videoId,
                    'key' => $this->apiKey,
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            $playlists = Playlist::where('user_id', auth()->id())->get();

            // Jika video tidak ditemukan
            if (empty($data['items'])) {
                return redirect()->route('youtube.index')->with('error', 'Video tidak ditemukan.');
            }

            $video = $data['items'][0];

            // Kirim data ke view
            return view('youtube.detail', compact('video', 'playlists'));
        } catch (\Exception $e) {
            return redirect()->route('youtube.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menambahkan video ke dalam playlist.
     */
    public function addToPlaylist(Request $request)
    {
        $request->validate([
            'playlist_id' => 'required|exists:playlists,id',
            'video_id' => 'required',
            'title' => 'required',
            'thumbnail' => 'required'
        ]);

        $playlist = Playlist::findOrFail($request->playlist_id);

        // Tambahkan video ke playlist
        PlaylistVideo::create([
            'playlist_id' => $playlist->id,
            'video_id' => $request->video_id,
            'title' => $request->title,
            'thumbnail' => $request->thumbnail
        ]);

        return redirect()->back()->with('success', 'Video berhasil ditambahkan ke playlist.');
    }
}
