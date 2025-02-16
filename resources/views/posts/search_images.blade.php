@extends('layouts.app')

@section('title', 'Hasil Pencarian - Social Media')

@section('content')
    <div class="py-6">
        <div class="flex items-center justify-center gap-3">
            <img src="{{ asset('images/icon.webp') }}" alt="" class="w-12 h-12 object-cover">
            @if ($query)
                <h2 class="text-3xl font-bold text-center">Result of : "{{ $query }}"</h2>
            @else
                <h2 class="text-3xl font-bold text-center">Explore <span class="text-blue-500">JustNews</span></h2>
            @endif
        </div>

        <form action="{{ route('search.images') }}" method="GET" class="w-full mt-8 flex gap-3">
            <input type="text" name="query" placeholder="Cari Gambar..." value="{{ $query }}"
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

        @if ($images->count() > 0)
            <div class="grid grid-cols-3 gap-4 mt-6">
                @foreach ($images as $image)
                    <div class="p-4 bg-white shadow-lg rounded-lg">
                        <a href="{{ route('posts.show', $image->id) }}">
                            <img src="{{ asset('storage/' . $image->image_path) }}"
                                class="w-full h-96 object-cover mt-2 rounded-lg">
                        </a>
                        <a href="{{ route('profile.show', ['name' => $image->user->name]) }}"
                            class="mt-4 flex items-start px-4 py-3 rounded-lg duration-500 transition-all hover:bg-gray-100">
                            <img src="{{ filter_var($image->user->profile_picture, FILTER_VALIDATE_URL) ? $image->user->profile_picture : asset('storage/' . ($image->user->profile_picture ?? 'profile_pictures/default.jpg')) }}"
                                alt="" class="w-10 h-10 rounded-full mr-4">
                            <div>
                                <h3 class="text-lg font-semibold">{{ $image->title }}</h3>
                                <p class="text-gray-600">{{ $image->description }}</p>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500">Tidak ada gambar ditemukan.</p>
        @endif
    </div>
@endsection
