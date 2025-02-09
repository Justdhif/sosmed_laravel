@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">Daftar Playlist {{ Auth::user()->name }}</h1>

        <a href="{{ route('playlist.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">
            Buat Playlist Baru
        </a>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
            @foreach ($playlists as $playlist)
                <div class="bg-white rounded-lg shadow p-4">
                    <img src="{{ $playlist->image ?? $playlist->videos->first()->thumbnail ?? asset('storage/profile_pictures/default.jpg') }}" alt="Playlist Thumbnail"
                        class="w-full h-40 object-cover rounded">
                    <h2 class="text-xl font-semibold mt-2">{{ $playlist->name }}</h2>
                    <p class="text-gray-600">{{ $playlist->description }}</p>
                    <a href="{{ route('playlist.show', $playlist->id) }}"
                        class="block bg-green-500 text-white px-4 py-2 mt-2 rounded text-center hover:bg-green-700">
                        Lihat Playlist
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endsection
