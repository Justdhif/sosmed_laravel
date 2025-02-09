@extends('layouts.app')

@section('title', 'Home - Social Media')

@section('content')

    <div>
        <!-- Story Section -->
        <div class="p-5 mb-6">
            <!-- Story User -->
            <div class="relative w-16 h-16">
                <!-- Gambar Profil dengan Border Gradient jika sudah punya Story -->
                <img src="{{ asset('storage/' . (auth()->user()->profile_picture ?? 'profile_pictures/default.jpg')) }}"
                    alt="Your Story" class="w-full h-full rounded-full object-cover border-2 border-gray-900">

                <h3 class="text-center font-bold">{{ auth()->user()->name }}</h3>
            </div>
        </div>

        @foreach ($posts as $post)
            <div class="bg-white flex flex-col rounded-lg px-40 mb-6">
                <!-- Header Postingan -->
                <div class="flex justify-between items-center py-4 border-b border-gray-300">
                    <a href="{{ route('profile.show', ['name' => $post->user->name]) }}" class="flex items-center">
                        <img src="{{ asset('storage/' . ($post->user->profile_picture ?? 'profile_pictures/default.jpg')) }}"
                            alt="User Profile" class="w-10 h-10 rounded-full mr-4">
                        <div>
                            <p class="font-semibold text-gray-800">{{ $post->user->name }}
                                {{ $post->user->followers->count() > 0 ? '🌟' : '' }}</p>
                            <p class="text-sm text-gray-500">{{ $post->created_at->diffForHumans() }}</p>
                        </div>
                    </a>
                    @if (auth()->user()->id !== $post->user->id)
                        <div class="relative right-0">
                            @if (auth()->user()->following->contains($post->user->id))
                                <form action="{{ route('unfollow', $post->user->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Unfollow</button>
                                </form>
                            @else
                                <form action="{{ route('follow', $post->user->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Follow</button>
                                </form>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Konten Postingan -->
                <div>
                    @if ($post->media->count() > 1)
                        <!-- Carousel -->
                        <div class="relative overflow-hidden rounded-lg">
                            <div class="carousel flex transition-transform duration-500" data-post-id="{{ $post->id }}">
                                @foreach ($post->media as $media)
                                    <div class="carousel-item flex-shrink-0 w-full">
                                        @if ($media->type == 'image')
                                            <img src="{{ asset('storage/' . $media->path) }}" alt="Post Image"
                                                class="w-full h-[32rem] object-cover">
                                        @elseif($media->type == 'video')
                                            <div class="video-container">
                                                <iframe width="560" height="315"
                                                    src="https://www.youtube.com/embed/{{ parse_url($media->path, PHP_URL_QUERY) }}"
                                                    frameborder="0"
                                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                    allowfullscreen></iframe>
                                            </div>
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
                                class="w-full h-[32rem] object-cover rounded-lg">
                        @elseif($post->media[0]->type == 'video')
                            <video controls class="w-full h-[32rem] object-cover">
                                <source src="{{ asset('storage/' . $post->media[0]->path) }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        @endif
                    @endif
                </div>

                <!-- Aksi Postingan -->
                <div class="py-4">
                    <div class="flex items-center gap-6">
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
                        <div class="flex items-start gap-2"
                            onclick="window.location.href='{{ route('posts.show', $post->id) }}'">
                            <i class="fa-regular fa-comment text-gray-600 text-2xl hover:text-indigo-600"></i>
                            <p class="text-lg font-bold text-gray-700">{{ $post->comments->count() }}</p>
                        </div>
                    </div>

                    <div class="py-4">
                        <p class="text-lg"><span class="font-bold">{{ $post->user->name }}</span> {{ $post->description }}
                        </p>
                    </div>

                    <a href="{{ route('posts.show', $post->id) }}"
                        class="font-semibold text-blue-500 hover:underline transition-all">See
                        {{ $post->comments->count() }}
                        comment(s)</a>

                    <form action="{{ route('posts.comment', $post->id) }}" method="POST" class="flex gap-2 h-12 mt-4">
                        @csrf
                        <textarea name="comment" rows="4"
                            class="w-full p-3 resize-none border border-gray-300 rounded-md focus:border-2 focus:border-indigo-700 focus:outline-none"
                            placeholder="Write a comment" required></textarea>
                        <button type="submit"
                            class="bg-indigo-600 text-white p-3 rounded-md hover:bg-indigo-700">Send</button>
                    </form>
                </div>
            </div>
        @endforeach
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
