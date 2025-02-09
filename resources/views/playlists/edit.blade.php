@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Edit Playlist</h1>

        <form action="{{ route('playlists.update', $playlist->id) }}" method="POST">
            @csrf
            @method('PUT')

            <label class="block mb-2">Nama Playlist</label>
            <input type="text" name="name" value="{{ $playlist->name }}" class="border px-4 py-2 rounded w-full mb-4">

            <label class="block mb-2">Deskripsi</label>
            <textarea name="description" class="border px-4 py-2 rounded w-full mb-4">{{ $playlist->description }}</textarea>

            <label class="block mb-2">Thumbnail (URL Gambar)</label>
            <input type="text" name="thumbnail" value="{{ $playlist->thumbnail }}"
                class="border px-4 py-2 rounded w-full mb-4">

            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Update</button>
        </form>
    </div>
@endsection
