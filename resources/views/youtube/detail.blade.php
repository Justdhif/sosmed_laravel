@extends('layouts.app')

@section('title', 'Detail Video - Social Media')

@section('content')
    <div class="p-4">
        <!-- Video Player -->
        <iframe src="https://www.youtube.com/embed/{{ $video['id'] }}" class="w-full h-[450px] rounded-lg"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen>
        </iframe>

        <!-- Video Details -->
        <div class="flex justify-center items-center gap-5 my-5">
            <img src="{{ $video['snippet']['thumbnails']['default']['url'] }}" alt=""
                class="w-14 h-14 object-cover rounded-full">
            <h1 class="text-2xl font-bold">{{ $video['snippet']['title'] }}</h1>
        </div>
        <p class="mt-2 text-gray-600">{{ $video['snippet']['description'] }}</p>

        <div class="flex justify-start items-center gap-3">
            <!-- Tombol Tambah ke Playlist -->
            <button onclick="document.getElementById('playlistModal').classList.remove('hidden')"
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700 mt-4">
                + Tambah ke Playlist
            </button>

            <!-- Back Button -->
            <a href="{{ route('youtube.index') }}" class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded">
                Kembali ke Beranda
            </a>
        </div>
    </div>

    <!-- Modal Pilih Playlist -->
    <div id="playlistModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h2 class="text-2xl font-semibold">Pilih Playlist</h2>

            <form action="{{ route('video.addToPlaylist') }}" method="POST">
                @csrf
                <input type="hidden" name="video_id" value="{{ $video['id'] }}">
                <input type="hidden" name="title" value="{{ $video['snippet']['title'] }}">
                <input type="hidden" name="thumbnail" value="{{ $video['snippet']['thumbnails']['high']['url'] }}">

                <select name="playlist_id" class="w-full border border-gray-300 p-2 rounded mt-2">
                    @foreach ($playlists as $playlist)
                        <option value="{{ $playlist->id }}">{{ $playlist->name }}</option>
                    @endforeach
                </select>

                <div class="flex justify-between mt-4">
                    <button type="button" onclick="document.getElementById('playlistModal').classList.add('hidden')"
                        class="px-4 py-2 bg-red-700 text-white rounded hover:bg-red-900">
                        Batal
                    </button>
                    <a href="{{ route('playlist.create') }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">
                        Buat Playlist
                    </a>
                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-700">
                        Tambahkan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
