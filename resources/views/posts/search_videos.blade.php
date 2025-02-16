@extends('layouts.app')

@section('title', 'Hasil Pencarian - Social Media')

@section('content')
    <div class="p-6">
        <div class="flex items-center justify-center gap-3">
            <img src="{{ asset('images/icon.webp') }}" alt="" class="w-12 h-12 object-cover">
            @if ($query)
                <h2 class="text-3xl font-bold text-center">Result of : "{{ $query }}"</h2>
            @else
                <h2 class="text-3xl font-bold text-center">Explore <span class="text-blue-500">JustNews</span></h2>
            @endif
        </div>

        <form action="{{ route('search.videos') }}" method="GET" class="w-full mt-8 flex gap-3">
            <input type="text" name="query" placeholder="Cari Video..." value="{{ $query }}"
                class="p-2 border-2 rounded-lg w-full focus:outline-none focus:border-blue-500">

            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg">🔍</button>
        </form>

        <!-- Tab Kategori -->
        <div class="mt-6 flex justify-between overflow-x-auto whitespace-nowrap space-x-3">
            <a href="{{ route('home', array_merge(request()->query(), ['category' => 'all'])) }}"
                class="px-4 py-2 rounded-lg duration-300 transition-all
            {{ request('category') == 'all' || !request('category') ? 'bg-blue-500 text-white' : 'bg-gray-200 hover:bg-blue-300 hover:text-white' }}">
                All
            </a>
            @foreach ($categories as $category)
                <a href="{{ route('home', array_merge(request()->query(), ['category' => $category->id])) }}"
                    class="px-4 py-2 rounded-lg duration-300 transition-all
            {{ request('category') == $category->id ? 'bg-blue-500 text-white' : 'bg-gray-200 hover:bg-blue-300 hover:text-white' }}">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>

        @if ($videos->count() > 0)
            @foreach ($videos as $video)
                <div class="mb-6 p-4 bg-white shadow-lg rounded-lg">
                    <div class="flex gap-5 mb-5 items-center">
                        <img src="{{ filter_var($video->user->profile_picture, FILTER_VALIDATE_URL) ? $video->user->profile_picture : asset('storage/' . ($video->user->profile_picture ?? 'profile_pictures/default.jpg')) }}"
                            alt="" class="w-14 h-14 rounded-full">
                        <div>
                            <h3 class="text-xl font-semibold">{{ $video->title }}</h3>
                            <p class="text-gray-600">{{ $video->description }}</p>
                        </div>
                    </div>
                    <iframe class="w-full mt-2 rounded-lg" height="500"
                        src="https://www.youtube.com/embed/{{ $video->youtube_video_id }}" allowfullscreen>
                    </iframe>
                </div>
            @endforeach
        @else
            <p class="text-gray-500">Tidak ada video ditemukan.</p>
        @endif
    </div>
@endsection
