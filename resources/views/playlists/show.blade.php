@extends('layouts.app')

@section('content')
    <div class="bg-white p-6">
        <h1 class="text-3xl font-bold">{{ $playlist->name }}</h1>
        <p class="text-gray-600">{{ $playlist->description }}</p>

        <img src="{{ $playlist->image ?? ($playlist->videos->first()->thumbnail ?? asset('storage/profile_pictures/default.jpg')) }}"
            class="w-full h-56 object-cover rounded mt-2">

        {{-- <h2 class="text-2xl font-semibold mt-4">Tambah Video</h2>
        <form action="{{ route('playlist.addVideo', $playlist->id) }}" method="POST" class="mt-2">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700">URL Video YouTube</label>
                <input type="url" name="video_url" class="w-full border border-gray-300 p-2 rounded"
                    placeholder="Masukkan URL video" required>
            </div>

            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-700">
                Tambah Video
            </button>
        </form> --}}
        <!-- Jika user adalah pemilik playlist -->
        @if (Auth::id() === $playlist->user_id)
            <div class="mt-4 flex space-x-2">
                <a href="{{ route('playlists.edit', $playlist->id) }}"
                    class="bg-blue-500 text-white px-4 py-2 rounded">Edit</a>

                <form action="{{ route('playlists.destroy', $playlist->id) }}" method="POST"
                    onsubmit="return confirm('Yakin ingin menghapus playlist ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Hapus</button>
                </form>
            </div>
        @endif

        <h2 class="text-2xl font-semibold mt-6">Daftar Video</h2>

        <!-- Form untuk memilih video yang ingin dihapus -->
        <form action="{{ route('playlist.removeSelectedVideos', $playlist->id) }}" method="POST"
            onsubmit="return confirm('Yakin ingin menghapus video yang dipilih?');">
            @csrf
            @method('DELETE')

            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded mt-4">Hapus Video yang Dipilih</button>

            <div class="grid grid-cols-2 gap-4 mt-4">
                @foreach ($playlist->videos as $video)
                    <div>
                        <iframe class="w-full h-56 rounded" src="https://www.youtube.com/embed/{{ $video->video_id }}"
                            allowfullscreen></iframe>
                        <h2 class="text-lg font-bold mt-2 mb-3">{{ $video->title }}</h2>
                        <input type="checkbox" name="videos[]" value="{{ $video->id }}" class="mr-2">
                        <a href="{{ route('video.detail', $video->video_id) }}"
                            class="bg-green-500 text-white font-bold px-4 py-2 rounded-lg mt-4">Detail video
                        </a>
                    </div>
                @endforeach
            </div>
        </form>
    </div>
@endsection
