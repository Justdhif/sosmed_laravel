@extends('layouts.app')

@section('title', 'Hasil Pencarian')

@section('content')
    <h2 class="text-2xl font-bold">Hasil Pencarian: "{{ $query }}"</h2>

    {{-- Hasil Pencarian Postingan --}}
    <div class="mt-6">
        <h3 class="text-xl font-semibold">📌 Postingan:</h3>
        @forelse($posts as $post)
            <div class="p-4 border rounded-lg shadow my-2">
                <h4 class="text-lg font-bold">{{ $post->title }}</h4>
                <p>{{ Str::limit($post->content, 100) }}</p>
                <a href="{{ route('posts.show', $post->id) }}" class="text-blue-600">Baca Selengkapnya</a>
            </div>
        @empty
            <p>Tidak ada postingan ditemukan.</p>
        @endforelse
    </div>

    {{-- Hasil Pencarian Akun Pengguna --}}
    <div class="mt-6">
        <h3 class="text-xl font-semibold">👤 Akun Pengguna:</h3>
        @forelse($users as $user)
            <div class="p-4 border rounded-lg shadow my-2">
                <h4 class="text-lg font-bold">{{ $user->name }}</h4>
                <p>Email: {{ $user->email }}</p>
                <a href="{{ route('profile.show', $user->id) }}" class="text-blue-600">Lihat Profil</a>
            </div>
        @empty
            <p>Tidak ada akun ditemukan.</p>
        @endforelse
    </div>

    {{-- Hasil Pencarian Video YouTube --}}
    <div class="mt-6">
        <h3 class="text-xl font-semibold">🎥 Video YouTube:</h3>
        @forelse($videos as $video)
            <div class="my-4">
                <h4 class="font-bold">{{ $video['snippet']['title'] }}</h4>
                <img
                    src="{{ $video['snippet']['thumbnails']['high']['url'] }}"
                    alt="{{ $video['snippet']['title'] }}"
                    class="w-[280px] h-[150px] rounded-lg object-cover border-2 border-gray-900"
                >
            </div>
        @empty
            <p>Tidak ada video ditemukan.</p>
        @endforelse
    </div>
@endsection
