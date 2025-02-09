@extends('layouts.app')

@section('title', 'Explore - Social Media')

@section('content')

    <div class="grid grid-cols-3 gap-1 p-4">
        @foreach ($posts as $post)
            <a href="{{ route('posts.show', $post->id) }}" class="aspect-w-1 aspect-h-1 relative">
                <img src="{{ asset('storage/' . $post->media->first()->path) }}" alt="Post Image"
                    class="w-full h-[350px] rounded-lg object-cover border-2 border-gray-900">

                <!-- Overlay Icon Jika Lebih dari 1 Media -->
                @if ($post->media->count() > 1)
                    <div class="absolute top-2 right-2 bg-black bg-opacity-50 text-white p-1 rounded-full">
                        <i class="fas fa-images"></i>
                    </div>
                @endif
            </a>
        @endforeach
    </div>

@endsection
