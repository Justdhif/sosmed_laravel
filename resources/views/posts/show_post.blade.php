@extends('layouts.app')

@section('title', 'Detail Postingan')

@section('content')
    <div class="p-6">
        {{-- Gambar Postingan --}}
        <img src="{{ asset('storage/' . $post->image_path) }}" alt="" class="w-full h-[40rem] object-cover rounded-xl">

        {{-- Judul Postingan --}}
        <h3 class="text-3xl font-bold mt-4">{{ $post->title }}</h3>

        {{-- Info Pemilik Postingan --}}
        <div class="flex justify-between items-center w-full mt-4">
            <div class="flex items-center gap-5">
                <img src="{{ filter_var($post->user->profile_picture, FILTER_VALIDATE_URL) ? $post->user->profile_picture : asset('storage/' . ($post->user->profile_picture ?? 'profile_pictures/default.jpg')) }}"
                    alt="" class="w-14 h-14 rounded-full">

                <div class="flex flex-col">
                    <p class="font-bold text-xl">{{ $post->user->name }}</p>
                    <p class="font-semibold text-sm text-gray-500">{{ $post->user->followers->count() }} Followers</p>
                </div>

                {{-- Follow/Unfollow Button --}}
                @if (auth()->id() !== $post->user_id)
                    @if (auth()->user()->isFollowing($post->user))
                        <form action="{{ route('unfollow', $post->user->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="px-4 py-2 bg-red-500 text-white rounded text-sm font-semibold">Unfollow</button>
                        </form>
                    @else
                        <form action="{{ route('follow', $post->user->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="px-4 py-2 bg-blue-500 text-white rounded text-sm font-semibold">Follow</button>
                        </form>
                    @endif
                @endif
            </div>

            {{-- Tombol Like --}}
            <div class="flex items-center gap-2">
                <form action="{{ route('posts.like', $post->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center bg-red-500 text-white text-xl gap-2 py-2 px-4 rounded">
                        <i
                            class="{{ $post->likes->contains('user_id', auth()->id()) ? 'fas' : 'fa-regular' }} fa-heart"></i>
                        <p class="text-lg font-bold text-white">{{ $post->likes->count() }}</p>
                    </button>
                </form>
            </div>
        </div>

        {{-- Deskripsi Postingan --}}
        <div class="bg-gray-100 p-4 rounded-lg mt-4">
            <h3 class="text-lg font-semibold">{{ $post->description }}</h3>
        </div>

        {{-- Komentar --}}
        <h3 class="text-2xl font-bold mt-5">{{ $post->comments->count() }} Comments </h3>

        <div class="shadow-md rounded-lg p-4 mt-4">
            {{-- Form Tambah Komentar --}}
            <form action="{{ route('posts.comment', $post->id) }}" method="POST" class="mt-5 flex items-start gap-4">
                @csrf
                <img src="{{ filter_var($post->user->profile_picture, FILTER_VALIDATE_URL) ? $post->user->profile_picture : asset('storage/' . ($post->user->profile_picture ?? 'profile_pictures/default.jpg')) }}"
                    alt="" class="w-14 h-14 rounded-full">
                <div class="flex flex-col w-full">
                    <h3 class="text-xl font-bold mb-2">{{ $post->user->name }}</h3>
                    <input type="text" name="comment" placeholder="Write a comment..."
                        class="w-full border-2 border-gray-300 rounded-lg py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="submit"
                        class="mt-2 w-1/4 bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-200">Comment</button>
                </div>
            </form>

            <h3 class="text-xl font-bold mt-6">All Comment</h3>

            {{-- Daftar Komentar --}}
            @foreach ($post->comments as $comment)
                <div class="mt-5 flex items-start gap-4">
                    <img src="{{ filter_var($comment->user->profile_picture, FILTER_VALIDATE_URL) ? $comment->user->profile_picture : asset('storage/' . ($comment->user->profile_picture ?? 'profile_pictures/default.jpg')) }}"
                        alt="" class="w-14 h-14 rounded-full">
                    <div class="flex flex-col w-full">
                        <h3 class="text-xl font-bold">{{ $comment->user->name }} <span
                                class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span></h3>
                        <p class="text-sm text-gray-600 mt-2">{{ $comment->comment }}</p>
                        <div class="flex items-center gap-3 mt-4">
                            {{-- Tombol Reply --}}
                            <button class="bg-blue-500 px-4 py-2 rounded text-white text-sm reply-btn">Reply</button>
                            {{-- Tombol Delete (Hanya untuk pemilik komentar) --}}
                            @if (Auth::id() === $comment->user_id)
                                <form action="{{ route('posts.comments.destroy', $comment->id) }}" method="POST"
                                    class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-500 px-4 py-2 rounded text-white text-sm">Delete</button>
                                </form>
                            @endif
                        </div>
                        {{-- Form Balasan Komentar (Hidden by Default) --}}
                        <form action="{{ route('posts.reply', $comment->id) }}" method="POST"
                            class="reply-form hidden mt-4">
                            @csrf
                            <textarea name="reply" rows="2"
                                class="w-full p-2 resize-none border border-gray-300 rounded-md focus:outline-none"
                                placeholder="Reply {{ $comment->user->name }}" required></textarea>
                            <div class="flex gap-2 mt-2">
                                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md">Reply</button>
                                <button type="button"
                                    class="bg-gray-400 text-white px-4 py-2 rounded-md cancel-reply">Cancel</button>
                            </div>
                        </form>

                        {{-- Balasan Komentar --}}
                        @foreach ($comment->replies as $reply)
                            <div class="ml-5 mt-4 border-l-2 border-gray-300 pl-4">
                                <div class="flex items-center gap-2">
                                    <img src="{{ filter_var($reply->user->profile_picture, FILTER_VALIDATE_URL) ? $reply->user->profile_picture : asset('storage/' . ($reply->user->profile_picture ?? 'profile_pictures/default.jpg')) }}"
                                        alt="" class="w-8 h-8 rounded-full">
                                    <p class="text-sm font-bold text-gray-800">{{ $reply->user->name }}</p>
                                    <span class="text-xs text-gray-500">{{ $reply->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-gray-600 mt-2">Replying to <span
                                        class="text-sm font-bold text-blue-500">{{ $reply->comment->user->name }}</span>
                                    {{ $reply->reply }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Script untuk menampilkan form reply --}}
    <script>
        document.querySelectorAll('.reply-btn').forEach(button => {
            button.addEventListener('click', function() {
                // Temukan form reply di dalam parent container komentar
                let replyForm = this.closest('.items-start').querySelector('.reply-form');

                // Tutup semua form reply sebelum membuka yang diklik
                document.querySelectorAll('.reply-form').forEach(form => {
                    if (form !== replyForm) {
                        form.classList.add('hidden');
                    }
                });

                // Toggle tampilan form reply yang diklik
                replyForm.classList.toggle('hidden');
            });
        });

        document.querySelectorAll('.cancel-reply').forEach(button => {
            button.addEventListener('click', function() {
                // Sembunyikan hanya form reply yang terkait dengan tombol cancel
                this.closest('.reply-form').classList.add('hidden');
            });
        });
    </script>
@endsection
