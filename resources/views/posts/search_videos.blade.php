@extends('layouts.app')

@section('content')
    <div class="container mx-auto max-w-4xl p-6">
        <form action="{{ route('search.videos') }}" method="GET" class="inline-block">
            <input type="text" name="query" placeholder="Cari Video..." class="p-2 border rounded-lg">
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg">🔍</button>
        </form>

        <h2 class="text-2xl font-bold text-blue-600 mb-4">Hasil Pencarian Video: "{{ $query }}"</h2>

        @if ($videos->count() > 0)
            @foreach ($videos as $video)
                <div class="mb-6 p-4 bg-white shadow-lg rounded-lg">
                    <h3 class="text-xl font-semibold">{{ $video->title }}</h3>
                    <p class="text-gray-600">{{ $video->description }}</p>
                    <iframe class="w-full mt-2 rounded-lg" height="300"
                        src="https://www.youtube.com/embed/{{ $video->youtube_video_id }}" allowfullscreen>
                    </iframe>
                </div>
            @endforeach
            {{ $videos->links() }}
        @else
            <p class="text-gray-500">Tidak ada video ditemukan.</p>
        @endif
    </div>
@endsection
