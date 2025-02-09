@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">Buat Playlist Baru</h1>

        <form action="{{ route('playlist.store') }}" method="POST" class="bg-white p-6 rounded-lg shadow">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700">Nama Playlist</label>
                <input type="text" name="name" class="w-full border border-gray-300 p-2 rounded"
                    placeholder="Nama Playlist (Opsional)">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Deskripsi</label>
                <textarea name="description" class="w-full border border-gray-300 p-2 rounded"
                    placeholder="Deskripsi playlist (Opsional)"></textarea>
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">
                Simpan Playlist
            </button>
        </form>
    </div>
@endsection
