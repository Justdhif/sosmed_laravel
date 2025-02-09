<aside class="absolute right-0 top-0 w-1/4 h-screen p-5 py-6 z-50 bg-white">
    <h1 class="text-lg font-semibold mb-4">Notifikasi</h1>
    @forelse($notifications as $notification)
        <div class="border-b py-3">
            <div class="{{ $notification->is_read === 1 ? 'bg-transparent' : 'bg-blue-200' }} p-3 px-4 rounded-lg">
                @if ($notification->type === 'like')
                    <div class="flex justify-between items-center gap-2">
                        <div class="flex items-start gap-3">
                            <img src="{{ asset('storage/' . ($notification->user->profile_picture ?? 'profile_pictures/default.jpg')) }}"
                                alt="Profile Picture" class="w-11 h-11 rounded-full object-cover">
                            <div>
                                <h3 class="text-md"><span class="font-bold">{{ $notification->actionUser->name }}
                                    </span>Liked your post.</h3>
                                <h3 class="text-sm font-light">
                                    {{ $notification->created_at->diffForHumans() }}</h3>
                            </div>
                        </div>
                        <a href="{{ route('posts.show', $notification->post_id) }}" class="text-blue-500">
                            <img src="{{ asset('storage/' . $notification->post->media->first()->path) }}"
                                alt="Post Image" class="w-11 h-11 rounded-lg object-cover border-2 border-gray-900">
                        </a>
                    </div>
                @elseif($notification->type === 'comment')
                    <div class="flex justify-between items-center gap-2">
                        <div class="flex items-start gap-3">
                            <img src="{{ asset('storage/' . ($notification->user->profile_picture ?? 'profile_pictures/default.jpg')) }}"
                                alt="Profile Picture" class="w-11 h-11 rounded-full object-cover">
                            <div>
                                <h3 class="text-md"><span class="font-bold">{{ $notification->actionUser->name }}
                                    </span>Comment your post.</h3>
                                <h3 class="text-sm font-light">
                                    {{ $notification->created_at->diffForHumans() }}</h3>
                            </div>
                        </div>
                        <a href="{{ route('posts.show', $notification->post_id) }}" class="text-blue-500">
                            <img src="{{ asset('storage/' . $notification->post->media->first()->path) }}"
                                alt="Post Image" class="w-11 h-11 rounded-lg object-cover border-2 border-gray-900">
                        </a>
                    </div>
                @elseif($notification->type === 'follow')
                    <div class="flex justify-between items-center gap-2">
                        <div class="flex items-start gap-3">
                            <img src="{{ asset('storage/' . ($notification->user->profile_picture ?? 'profile_pictures/default.jpg')) }}"
                                alt="Profile Picture" class="w-11 h-11 rounded-full object-cover">
                            <div>
                                <h3 class="text-md"><span class="font-bold">{{ $notification->actionUser->name }}
                                    </span>Followed you.</h3>
                                <h3 class="text-sm font-light">
                                    {{ $notification->created_at->diffForHumans() }}</h3>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            @if (!$notification->is_read)
                <form action="{{ route('notifications.read', $notification->id) }}" method="POST" class="mt-3">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="text-sm text-green-500">Tandai Dibaca</button>
                </form>
            @endif
        </div>
    @empty
        <p>Tidak ada notifikasi baru.</p>
    @endforelse
</aside>
