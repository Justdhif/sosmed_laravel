@extends('layouts.app')

@section('title', 'Video Explore - Social Media')

@section('content')
    <!-- Search Bar -->
    <form action="{{ route('youtube.index') }}" method="GET" class="mb-4 w-full flex justify-center gap-4">
        <input type="text" name="query" class="p-2 border border-gray-300 rounded w-1/2 focus:outline-blue-500"
            placeholder="Cari video YouTube..." value="{{ $query ?? '' }}" required>
        <button type="submit" class="bg-blue-500 text-white text-bold px-4 py-2 rounded">Cari</button>
    </form>

    <!-- Video Grid -->
    <div class="grid grid-cols-4 gap-4 p-4">
        @foreach ($videos as $video)
            @if (isset($video['id']['videoId']) && isset($video['snippet']['thumbnails']['high']['url']))
                <a href="{{ route('video.detail', $video['id']['videoId']) }}">
                    <img src="{{ $video['snippet']['thumbnails']['high']['url'] }}"
                        alt="{{ $video['snippet']['title'] ?? 'Video Tanpa Judul' }}"
                        class="w-[280px] h-[150px] rounded-lg object-cover border-2 border-gray-900">
                    <h2 class="mt-2 text-sm font-semibold">{{ $video['snippet']['title'] ?? 'Tanpa Judul' }}</h2>
                </a>
            @endif
        @endforeach
    </div>
@endsection
