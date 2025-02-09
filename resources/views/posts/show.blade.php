@extends('layouts.app')

@section('title', 'Detail Postingan')

@section('content')
    <div class="h-screen flex items-start gap-4 overflow-hidden">
        <!-- Detail Postingan -->
        <div class="relative overflow-hidden flex justify-center items-center w-3/5 h-full bg-gray-900 p-52">
            @if ($post->media->count() > 1)
                <!-- Carousel -->
                <div class="relative overflow-hidden rounded-s-lg">
                    <div class="carousel flex transition-transform duration-500" data-post-id="{{ $post->id }}">
                        @foreach ($post->media as $media)
                            <div class="carousel-item flex-shrink-0 w-full">
                                @if ($media->type == 'image')
                                    <img src="{{ asset('storage/' . $media->path) }}" alt="Post Image"
                                        class="w-full h-full object-cover">
                                @elseif($media->type == 'video')
                                    <video controls class="w-full h-full object-cover">
                                        <source src="{{ asset('storage/' . $media->path) }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    <!-- Prev and Next Buttons -->
                    <button
                        class="absolute top-1/2 left-4 transform -translate-y-1/2 bg-blue-500 text-white w-8 h-8 rounded-full opacity-80 carousel-prev hover:opacity-100 transition-all"
                        data-post-id="{{ $post->id }}">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button
                        class="absolute top-1/2 right-4 transform -translate-y-1/2 bg-blue-500 text-white w-8 h-8 rounded-full opacity-80 carousel-next hover:opacity-100 transition-all"
                        data-post-id="{{ $post->id }}">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            @elseif($post->media->count() == 1)
                <!-- Single Media -->
                @if ($post->media[0]->type == 'image')
                    <img src="{{ asset('storage/' . $post->media[0]->path) }}" alt="Post Image"
                        class="w-full h-full object-cover">
                @elseif($post->media[0]->type == 'video')
                    <video controls class="w-full h-full object-cover">
                        <source src="{{ asset('storage/' . $post->media[0]->path) }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                @endif
            @endif
        </div>

        <!-- Komentar dan Aksi -->
        <div class=" relative py-4 w-2/5 h-full bg-white px-4">
            <div class="flex items-center py-4 border-b border-gray-300">
                <img src="{{ asset('storage/' . ($post->user->profile_picture ?? 'profile_pictures/default.jpg')) }}"
                    alt="User Profile" class="w-10 h-10 rounded-full mr-4">
                <div>
                    <p class="font-semibold text-gray-800">{{ $post->user->name }}</p>
                    <p class="text-sm text-gray-500">{{ $post->created_at->diffForHumans() }}</p>
                </div>
            </div>
            <p class="font-bold text-2xl mb-2 mt-4">{{ $post->title }}</p>
            <p class="text-lg font-bold text-gray-700 mb-2">{{ $post->description }}</p>
            <!-- Jika user adalah pemilik postingan -->
            @if (Auth::id() === $post->user_id)
                <div class="mt-4 flex space-x-2 mb-4">
                    <a href="{{ route('posts.edit', $post->id) }}"
                        class="bg-blue-500 text-white px-4 py-2 rounded">Edit</a>

                    <form action="{{ route('posts.destroy', $post->id) }}" method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus postingan ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Hapus</button>
                    </form>
                </div>
            @endif
            <div class="flex items-center gap-6 mb-4">
                <form action="{{ route('posts.like', $post->id) }}" method="POST" class="flex items-start gap-2">
                    @csrf
                    <button type="submit" class="text-gray-600 text-2xl hover:text-red-600">
                        @if ($post->likes->contains('user_id', auth()->id()))
                            <i class="fas fa-heart text-red-600"></i>
                        @else
                            <i class="fa-regular fa-heart"></i>
                        @endif
                    </button>
                    <p class="text-lg font-bold text-gray-700">{{ $post->likes->count() }}</p>
                </form>
                <div class="flex items-start gap-2">
                    <i class="fa-regular fa-comment text-gray-600 text-2xl hover:text-indigo-600"></i>
                    <p class="text-lg font-bold text-gray-700">{{ $post->comments->count() }}</p>
                </div>
            </div>
            <div class="bg-gray-300 text-wh p-3 px-4 w-full flex justify-between items-center">
                <input type="text" id="link" value="http://127.0.0.1:8000/posts/{{ $post->id }}" readonly
                    class="font-bold w-3/4 bg-transparent focus:outline-none">
                <button class="font-bold" onclick="copyLink()">Copy Link</button>
            </div>
            <div class="py-4">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Comments</h3>
                <div id="comments-container" class="space-y-4 overflow-y-scroll h-screen">
                    @foreach ($comments as $comment)
                        <div class="flex items-start gap-4 border-b border-gray-200 pb-4">
                            <img src="{{ asset('storage/' . ($comment->user->profile_picture ?? 'profile_pictures/default.jpg')) }}"
                                alt="User Profile" class="w-8 h-8 rounded-full">
                            <div>
                                <p class="text-sm font-bold text-gray-800">{{ $comment->user->name }}</p>
                                <p class="text-sm text-gray-600 mt-2">{{ $comment->comment }}</p>
                                <p class="text-xs text-gray-500 mt-4">{{ $comment->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <form action="{{ route('posts.comment', $post->id) }}" method="POST"
                class="absolute bottom-0 left-0 w-full flex gap-2">
                @csrf
                <textarea name="comment" rows="2"
                    class="w-full p-2 resize-none border border-gray-300 rounded-md focus:outline-none" placeholder="Write a comment"
                    required></textarea>
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700"><i
                        class="fa-solid fa-paper-plane"></i></button>
            </form>
        </div>
    </div>

    <script>
        document.querySelectorAll('.carousel').forEach(carousel => {
            let currentIndex = 0;
            const items = carousel.querySelectorAll('.carousel-item');
            const totalItems = items.length;
            const postId = carousel.dataset.postId;

            const updateCarousel = () => {
                const offset = -currentIndex * 100;
                carousel.style.transform = `translateX(${offset}%)`;
            };

            document.querySelector(`.carousel-prev[data-post-id="${postId}"]`).addEventListener('click', () => {
                currentIndex = (currentIndex === 0) ? totalItems - 1 : currentIndex - 1;
                updateCarousel();
            });

            document.querySelector(`.carousel-next[data-post-id="${postId}"]`).addEventListener('click', () => {
                currentIndex = (currentIndex + 1) % totalItems;
                updateCarousel();
            });
        });
    </script>

@endsection
