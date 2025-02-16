@extends('layouts.app')

@section('title', 'Profil')

@section('content')
    <div class="p-4 bg-white rounded-lg">
        <!-- Background Image -->
        <div class="relative mb-5 rounded-lg overflow-hidden">
            <img src="{{ asset('storage/' . ($user->background_image ?? 'profile_backgrounds/default-bg.jpg')) }}"
                class="w-full h-64 object-cover">
            <div class="absolute inset-0 bg-black bg-opacity-30"></div>
        </div>

        <!-- Profile Header -->
        <div class="flex items-center gap-6 pb-6 border-b border-gray-300">
            <!-- Profile Picture -->
            <img src="{{ filter_var($user->profile_picture, FILTER_VALIDATE_URL) ? $user->profile_picture : asset('storage/' . ($user->profile_picture ?? 'profile_pictures/default.jpg')) }}"
                alt="Profile Picture" class="w-24 h-24 rounded-full object-cover">

            <!-- Profile Info -->
            <div>
                <div class="flex items-center gap-4">
                    <h1 class="text-2xl font-bold">{{ $user->name }}</h1>
                    <div class="flex gap-1">
                        @if ($user->followers->count() >= 1)
                            <img src="{{ asset('images/verified.png') }}" alt=""
                                class="w-7 h-7 border-2 border-black rounded-full">
                            <img src="https://img.icons8.com/ios-filled/50/ffffff/trophy.png" alt="Trophy Icon">
                        @endif
                        @if ($user->posts->count() >= 10)
                            <img src="{{ asset('images/star.png') }}" alt=""
                                class="w-7 h-7 border-2 border-black rounded-full">
                        @endif
                        @if ($user->shopProfile)
                            <img src="{{ asset('images/perunggu.png') }}" alt=""
                                class="w-7 h-7 border-2 border-black rounded-full">
                        @endif
                        @if ($user->shopProfile->products->count() >= 1)
                            <img src="{{ asset('images/silver.png') }}" alt=""
                                class="w-7 h-7 border-2 border-black rounded-full">
                        @endif
                        @if ($user->shopProfile->products->count() >= 5)
                            <img src="{{ asset('images/gold.png') }}" alt=""
                                class="w-7 h-7 border-2 border-black rounded-full">
                        @endif
                    </div>
                    @if (auth()->id() === $user->id)
                        <a href="{{ route('profile.edit', Auth::user()->id) }}"
                            class="px-4 py-2 bg-gray-200 rounded text-sm font-semibold">
                            Edit Profil
                        </a>
                    @else
                        @if (auth()->user()->isFollowing($user))
                            <!-- Tombol Unfollow -->
                            <form action="{{ route('unfollow', $user->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="px-4 py-2 bg-red-500 text-white rounded text-sm font-semibold">Unfollow</button>
                            </form>
                        @else
                            <!-- Tombol Follow -->
                            <form action="{{ route('follow', $user->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="px-4 py-2 bg-blue-500 text-white rounded text-sm font-semibold">Follow</button>
                            </form>
                        @endif
                    @endif
                    <button class="text-2xl">⚙️</button>
                </div>
                <div class="flex gap-6 mt-3">
                    <span class="text-lg font-bold"><strong>{{ $user->posts->count() }}</strong> Post</span>
                    <span class="text-lg font-bold"><strong>{{ $user->followers->count() }}</strong> Followers</span>
                    <span class="text-lg font-bold"><strong>{{ $user->following->count() }}</strong> Following</span>
                </div>
            </div>
        </div>

        <!-- Bio -->
        <div class="py-4">
            @if (!$user->bio)
                <p class="text-gray-600">Tidak ada bio</p>
            @else
                <p class="text-gray-500 ">{{ $user->bio }}</p>
            @endif
        </div>

        <!-- Shop Profile Section -->
        <div class="py-6 border-t border-gray-300">
            @if ($user->shopProfile)
                <!-- Display Shop Profile -->
                <div class="flex items-center gap-6">
                    <div class="relative">
                        <img src="{{ asset('storage/' . ($shopProfile->image_path ?? 'shop_profiles/default-shop.jpg')) }}"
                            alt="{{ $shopProfile->name }}" class="w-24 h-24 rounded-full object-cover">
                        <a href="{{ route('products.create') }}"
                            class="absolute right-0 bottom-0 w-8 h-8 rounded-full bg-blue-400 flex justify-center items-center"><i
                                class="fa-solid fa-shop text-sm text-white"></i>
                        </a>
                    </div>
                    <div>
                        <div class="flex items-center gap-4">
                            <h1 class="text-2xl font-bold">{{ $user->shopProfile->name }}</h1>
                            @if (auth()->id() === $user->id)
                                <a href="{{ route('shop.profile.edit', $shopProfile->id) }}"
                                    class="px-4 py-2 bg-gray-200 rounded text-sm font-semibold">Edit
                                    Profil</a>
                            @else
                                @if (auth()->user()->isFollowing($user))
                                    <!-- Tombol Unfollow -->
                                    <form action="{{ route('unfollow', $user->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="px-4 py-2 bg-red-500 text-white rounded text-sm font-semibold">Unfollow</button>
                                    </form>
                                @else
                                    <!-- Tombol Follow -->
                                    <form action="{{ route('follow', $user->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="px-4 py-2 bg-blue-500 text-white rounded text-sm font-semibold">Follow</button>
                                    </form>
                                @endif
                            @endif
                        </div>
                        <div class="flex items-center mt-3 gap-2">
                            <span class="text-lg font-bold"><strong>{{ $productCount }}</strong> Product</span>
                            <span class="text-lg font-bold"><strong>{{ number_format($averageRating, 1) }}</strong>
                                ⭐</span>
                        </div>
                    </div>
                </div>

                <div class="py-4">
                    @if (!$user->shopProfile->description)
                        <p class="text-gray-600">Tidak ada bio</p>
                    @else
                        <p class="text-gray-500 ">{{ $user->shopProfile->description }}</p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Tab Navigation -->
        <div class="flex justify-between w-full mb-6">
            <button id="my-posts-tab" class="tab-button w-full active" onclick="showTab('my-posts', this)">Postingan
                Saya</button>
            <button id="liked-posts-tab" class="tab-button w-full" onclick="showTab('liked-posts', this)">Postingan yang
                Disukai</button>
            <button id="products-tab" class="tab-button w-full" onclick="showTab('products', this)">Produk</button>
        </div>

        <!-- My Posts Tab Content -->
        <div id="my-posts" class="w-full mt-4">
            <div class="flex mb-4">
                <button class="tab-button w-1/2 active" onclick="showSubTab('my-posts-images', this)">Gambar</button>
                <button class="tab-button w-1/2" onclick="showSubTab('my-posts-videos', this)">Video</button>
            </div>

            <!-- Gambar Postingan -->
            <div id="my-posts-images" class="w-full">
                @if ($posts->isEmpty())
                    <div class="flex justify-center items-center w-full">
                        <p class="text-gray-600">Postingan Anda kosong.</p>
                    </div>
                @else
                    <div class="grid grid-cols-3 gap-2 w-full mb-6">
                        @foreach ($posts as $post)
                            @if ($post->image_path)
                                <a href="{{ route('posts.show', $post->id) }}" class="relative group">
                                    <img src="{{ asset('storage/' . $post->image_path) }}" alt="Post Image"
                                        class="w-full h-72 rounded-lg object-cover border-2 border-gray-900">
                                </a>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Video Postingan -->
            <div id="my-posts-videos" class="w-full hidden">
                @if ($posts->isEmpty())
                    <div class="flex justify-center items-center w-full">
                        <p class="text-gray-600">Postingan Anda kosong.</p>
                    </div>
                @else
                    <div class="grid grid-cols-3 gap-2 w-full">
                        @foreach ($posts as $post)
                            @if ($post->youtube_video_id)
                                <a href="{{ route('posts.show', $post->id) }}" class="relative group">
                                    <iframe width="100%" height="288"
                                        src="https://www.youtube.com/embed/{{ $post->youtube_video_id }}"
                                        title="YouTube video player" frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen class="rounded-lg"></iframe>
                                </a>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <div id="liked-posts" class="w-full mt-4 hidden">
            <div class="flex mb-4">
                <button class="tab-button w-1/2 active" onclick="showSubTab('liked-posts-images', this)">Gambar</button>
                <button class="tab-button w-1/2" onclick="showSubTab('liked-posts-videos', this)">Video</button>
            </div>

            <!-- Gambar Postingan yang Disukai -->
            <div id="liked-posts-images" class="w-full">
                @if ($likedPosts->isEmpty())
                    <div class="flex justify-center items-center w-full">
                        <p class="text-gray-600">Belum ada postingan yang disukai.</p>
                    </div>
                @else
                    <div class="grid grid-cols-3 gap-2 w-full mb-6">
                        @foreach ($likedPosts as $post)
                            @if ($post->image_path)
                                <a href="{{ route('posts.show', $post->id) }}" class="relative group">
                                    <img src="{{ asset('storage/' . $post->image_path) }}" alt="Post Image"
                                        class="w-full h-72 rounded-lg object-cover border-2 border-gray-900">
                                </a>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Video Postingan yang Disukai -->
            <div id="liked-posts-videos" class="w-full hidden">
                @if ($likedPosts->isEmpty())
                    <div class="flex justify-center items-center w-full">
                        <p class="text-gray-600">Belum ada postingan yang disukai.</p>
                    </div>
                @else
                    <div class="grid grid-cols-3 gap-2 w-full">
                        @foreach ($likedPosts as $post)
                            @if ($post->youtube_video_id)
                                <a href="{{ route('posts.show', $post->id) }}" class="relative group">
                                    <iframe width="100%" height="288"
                                        src="https://www.youtube.com/embed/{{ $post->youtube_video_id }}"
                                        title="YouTube video player" frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen class="rounded-lg"></iframe>
                                </a>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Produk Grid -->
        <div id="products" class="w-full mt-4 hidden">
            @if (!$shopProfile)
                <div class="flex flex-col justify-center items-center w-full">
                    <p class="text-gray-600">Anda belum memiliki profil toko.</p>
                    <a href="{{ route('shop.profile.create') }}"
                        class="bg-blue-500 text-white px-4 py-2 rounded mt-2 inline-block">
                        Buat Profil Toko
                    </a>
                </div>
            @elseif ($shopProfile->products->isEmpty())
                <div class="flex justify-center items-center w-full">
                    <p class="text-gray-600">Belum ada produk yang tersedia.</p>
                </div>
            @else
                <div class="grid grid-cols-3 gap-4">
                    @foreach ($shopProfile->products as $product)
                        <div class="border p-4 rounded-lg">
                            <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}"
                                class="w-full h-40 object-cover rounded">
                            <h4 class="mt-2 font-bold">{{ $product->name }}</h4>
                            <h4 class="text-gray-600 font-semibold">Rp
                                {{ number_format($product->price, 0, ',', '.') }}
                            </h4>
                            <p>Stock : <span
                                    class="{{ $product->stock > 0 ? 'text-green-500' : 'text-red-500' }}">{{ $product->stock }}</span>
                            </p>
                            <br>
                            <div class="flex justify-start items-center gap-4">

                                <a href="{{ route('products.show', $product->id) }}"
                                    class="text-white bg-blue-500 px-4 py-2 rounded text-sm">Lihat Produk</a>
                                <form action="{{ route('products.destroy', $product->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-white bg-red-500 px-4 py-2 rounded text-sm">Hapus
                                        Produk</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <script>
        function showTab(tabId, element) {
            document.getElementById('my-posts').classList.add('hidden');
            document.getElementById('liked-posts').classList.add('hidden');
            document.getElementById('products').classList.add('hidden');
            document.getElementById(tabId).classList.remove('hidden');

            document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
            element.classList.add('active');
        }

        function showSubTab(subTabId, element) {
            const parentTab = element.closest('div[id^="my-posts"]') || element.closest('div[id^="liked-posts"]');
            parentTab.querySelectorAll('[id$="-images"], [id$="-videos"]').forEach(tab => tab.classList.add('hidden'));
            parentTab.querySelector(`#${subTabId}`).classList.remove('hidden');

            parentTab.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
            element.classList.add('active');
        }
    </script>

    <style>
        .tab-button {
            padding: 10px 16px;
            font-weight: bold;
            border-bottom: 2px solid rgba(143, 143, 143, 0.5);
            cursor: pointer;
        }

        .tab-button.active {
            border-bottom: 2px solid black;
            color: black;
        }
    </style>
@endsection
