@extends('layouts.app')

@section('title', 'Home - Social Media')

@section('content')

    <div class="py-6">
        <div class="flex items-center justify-center gap-3">
            <img src="{{ asset('images/icon.webp') }}" alt="" class="w-12 h-12 object-cover">
            <h2 class="text-3xl font-bold text-center">welcome to JustNews <span
                    class="text-indigo-600">{{ auth()->user()->name }}</span></h2>
        </div>

        <form action="{{ route('home') }}" method="GET" class="w-full mt-8 flex gap-3">
            <input type="text" name="query" value="{{ request('query') }}" placeholder="Cari Gambar..."
                class="p-2 border-2 rounded-lg w-full focus:outline-none focus:border-blue-500">
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg">🔍</button>
        </form>

        <!-- Tab Kategori -->
        <div class="mt-6 flex overflow-x-auto whitespace-nowrap space-x-3">
            <a href="{{ route('home', array_merge(request()->query(), ['category' => 'all'])) }}" class="px-4 py-2 rounded-lg bg-gray-200 duration-300 transition-all hover:bg-indigo-500 hover:text-white
                {{ request('category') == 'all' || !request('category') == 'all' ? 'bg-indigo-500 text-white' : '' }}">
                All
            </a>
            <a href="{{ route('home', array_merge(request()->query(), ['category' => 'foto'])) }}"
                class="px-4 py-2 rounded-lg duration-300 transition-all bg-gray-200 hover:bg-indigo-500 hover:text-white
                {{ request('category') == 'foto' ? 'bg-indigo-500 text-white' : '' }}">
                Foto
            </a>
            <a href="{{ route('home', array_merge(request()->query(), ['category' => 'video'])) }}"
                class="px-4 py-2 rounded-lg duration-300 transition-all bg-gray-200 hover:bg-indigo-500 hover:text-white
                {{ request('category') == 'video' ? 'bg-indigo-500 text-white' : '' }}">
                Video
            </a>
            @foreach ($categories as $category)
                <a href="{{ route('home', array_merge(request()->query(), ['category' => $category->id])) }}"
                    class="px-4 py-2 rounded-lg bg-gray-200 duration-300 transition-all hover:bg-indigo-500 hover:text-white
                {{ request('category') == $category->id ? 'bg-indigo-500 text-white' : '' }}">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>

        @foreach ($videos->chunk(2) as $chunk)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($chunk as $video)
                    <div class="bg-white rounded-lg px-4 py-6">
                        <!-- Header -->
                        <div class="flex justify-between items-center py-4 border-b border-gray-300">
                            <a href="{{ route('profile.show', ['name' => $video->user->name]) }}" class="flex items-center">
                                <img src="{{ filter_var($video->user->profile_picture, FILTER_VALIDATE_URL) ? $video->user->profile_picture : asset('storage/' . ($video->user->profile_picture ?? 'profile_pictures/default.jpg')) }}"
                                    alt="User Profile" class="w-10 h-10 rounded-full mr-4">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $video->user->name }}
                                        {{ $video->user->followers->count() > 0 ? '🌟' : '' }}</p>
                                    <p class="text-sm text-gray-500">{{ $video->created_at->diffForHumans() }}</p>
                                </div>
                            </a>
                            {{-- Button Follow --}}
                            @if (auth()->id() !== $video->user_id)
                                @if (auth()->user()->isFollowing($video->user))
                                    <form action="{{ route('unfollow', $video->user->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="px-4 py-2 bg-red-500 text-white rounded text-sm font-semibold">Unfollow</button>
                                    </form>
                                @else
                                    <form action="{{ route('follow', $video->user->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="px-4 py-2 bg-blue-500 text-white rounded text-sm font-semibold">Follow</button>
                                    </form>
                                @endif
                            @endif
                        </div>

                        <!-- Video Konten -->
                        <div class="w-full h-[24rem] rounded-lg overflow-hidden">
                            @if ($video->youtube_video_id)
                                @php
                                    preg_match(
                                        '/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]+)/',
                                        $video->youtube_video_id,
                                        $matches,
                                    );
                                    $videoId = $matches[1] ?? $video->youtube_video_id;
                                @endphp

                                <iframe width="100%" height="100%"
                                    src="https://www.youtube.com/embed/{{ $videoId }}" frameborder="0"
                                    allowfullscreen>
                                </iframe>
                            @endif
                        </div>

                        <!-- Aksi Video -->
                        <div class="py-4">
                            <div class="flex items-center gap-6">
                                <form action="{{ route('posts.like', $video->id) }}" method="post"
                                    class="flex items-start gap-2">
                                    @csrf
                                    <button type="submit" class="text-gray-600 text-2xl hover:text-red-600">
                                        @if ($video->likes->contains('user_id', auth()->id()))
                                            <i class="fas fa-heart text-red-600"></i>
                                        @else
                                            <i class="fa-regular fa-heart"></i>
                                        @endif
                                    </button>
                                    <p class="text-lg font-bold text-gray-700">{{ $video->likes->count() }}</p>
                                </form>
                                <div class="flex items-start gap-2"
                                    onclick="window.location.href='{{ route('posts.show', $video->id) }}'">
                                    <i class="fa-regular fa-comment text-gray-600 text-2xl hover:text-indigo-600"></i>
                                    <p class="text-lg font-bold text-gray-700">{{ $video->comments->count() }}</p>
                                </div>
                            </div>

                            <div class="py-4">
                                <p class="text-lg"><span class="font-bold">{{ $video->user->name }}</span>
                                    {{ $video->description }}
                                </p>
                            </div>

                            <a href="{{ route('posts.show', $video->id) }}"
                                class="font-semibold text-blue-500 hover:underline transition-all">
                                See {{ $video->comments->count() }} comment(s)
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- <style>
                .border-gradient {
                    border: 4px solid transparent;
                    border-radius: 100%;
                    /* Sesuaikan dengan rounded-lg */
                    background-image: linear-gradient(white, white), linear-gradient(to right, #ff7e5f, #feb47b);
                    background-clip: padding-box, border-box;
                    background-origin: border-box;
                }
            </style> --}}

            <!-- Setiap 2 row video, tambahkan 1 row gambar -->
            @if ($loop->iteration % 2 == 0)
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
                    @foreach ($images->slice(($loop->iteration / 2 - 1) * 4, 4) as $image)
                        <div class="bg-white shadow-lg rounded-lg p-4">
                            <a href="{{ route('posts.show', $image->id) }}">
                                <img src="{{ asset('storage/' . $image->image_path) }}"
                                    class="w-72 h-72 object-cover mt-2 rounded-lg">
                            </a>
                            <div class="flex items-center mt-4">
                                <img src="{{ filter_var($video->user->profile_picture, FILTER_VALIDATE_URL) ? $video->user->profile_picture : asset('storage/' . ($video->user->profile_picture ?? 'profile_pictures/default.jpg')) }}"
                                    class="w-10 h-10 rounded-full mr-2">
                                <div class="flex items-center">
                                    <p class="font-semibold text-gray-800">{{ $image->user->name }}</p>
                                    <p class="text-sm text-gray-500 ml-2">{{ $image->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <h3 class="text-sm font-semibold mt-2">{{ $image->title }}</h3>
                            <p class="text-sm text-gray-500 mt-2">{{ $image->description }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        @endforeach
    </div>

    <!-- Menampilkan Gambar -->
    @if(request('category') !== 'video')
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
            @foreach ($images as $image)
                <div class="bg-white shadow-lg rounded-lg p-4">
                    <a href="{{ route('posts.show', $image->id) }}">
                        <img src="{{ asset('storage/' . $image->image_path) }}" class="w-72 h-72 object-cover mt-2 rounded-lg">
                    </a>
                    <div class="flex items-center mt-4">
                        <img src="{{ filter_var($image->user->profile_picture, FILTER_VALIDATE_URL) ? $image->user->profile_picture : asset('storage/' . ($image->user->profile_picture ?? 'profile_pictures/default.jpg')) }}"
                            class="w-10 h-10 rounded-full mr-2">
                        <div class="flex items-center">
                            <p class="font-semibold text-gray-800">{{ $image->user->name }}</p>
                            <p class="text-sm text-gray-500 ml-2">{{ $image->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <h3 class="text-sm font-semibold mt-2">{{ $image->title }}</h3>
                    <p class="text-sm text-gray-500 mt-2">{{ $image->description }}</p>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $images->links() }}
        </div>
    @endif
@endsection
