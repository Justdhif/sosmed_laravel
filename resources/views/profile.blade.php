@extends('layouts.app')

@section('content')
    <div class="p-4 bg-white rounded-lg">
        <!-- Background Image -->
        <div class="relative mb-5 rounded-lg overflow-hidden">
            <img src="{{ asset('storage/' . ($user->background_image ?? 'profile_backgrounds/default.jpg')) }}"
                class="w-full h-64 object-cover">
            <div class="absolute inset-0 bg-black bg-opacity-30"></div>
        </div>

        <!-- Profile Header -->
        <div class="flex items-center gap-6 pb-6 border-b border-gray-300">
            <!-- Profile Picture -->
            <img src="{{ asset('storage/' . ($user->profile_picture ?? 'profile_pictures/default.jpg')) }}"
                alt="Profile Picture" class="w-24 h-24 rounded-full object-cover">

            <!-- Profile Info -->
            <div>
                <div class="flex items-center gap-4">
                    <h1 class="text-2xl font-bold">{{ $user->name }}</h1>
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
            </a>

            <div class="grid grid-cols-4 gap-4 mt-4">
                <a href="{{ route('playlist.create') }}"
                    class="w-full bg-gray-100 hover:bg-gray-200 text-black rounded-lg flex justify-start items-center gap-3 mb-4 overflow-hidden">
                    <div class="w-1/5 h-full flex justify-center items-center bg-blue-500">
                        <i class="fa-regular fa-plus text-white"></i>
                    </div>
                    <h2 class="text-md font-semibold">Playlist baru</h2>
                </a>
                @foreach ($playlists as $playlist)
                    <a href="{{ route('playlist.show', $playlist->id) }}"
                        class="w-full bg-gray-100 hover:bg-gray-200 text-black rounded-lg flex justify-start items-center gap-3 mb-4 overflow-hidden">
                        <img src="{{ $playlist->image ?? ($playlist->videos->first()->thumbnail ?? asset('storage/profile_pictures/default.jpg')) }}"
                            alt="Playlist Thumbnail" class="w-1/5 h-full object-cover aspect-square">
                        <h2 class="text-md font-semibold">{{ $playlist->name }}</h2>
                    </a>
                @endforeach
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
                                    <a href="{{  route('shop.profile.edit', $shopProfile->id) }}" class="px-4 py-2 bg-gray-200 rounded text-sm font-semibold">Edit
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
                            <div class="flex items-center gap-2">
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

            <!-- Posts Grid -->
            <div id="my-posts" class="w-full mt-4">
                @if ($posts->isEmpty())
                    <div class="flex justify-center items-center w-full">
                        <p class="text-gray-600">Postingan Anda kosong.</p>
                    </div>
                @else
                    <div class="grid grid-cols-3 gap-2 w-full">
                        @foreach ($posts as $post)
                            <a href="{{ route('posts.show', $post->id) }}" class="relative group">
                                <img src="{{ asset('storage/' . $post->media->first()->path) }}" alt="Post Image"
                                    class="w-full h-72 rounded-lg object-cover border-2 border-gray-900">
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <div id="liked-posts" class="w-full mt-4 hidden">
                @if ($likedPosts->isEmpty())
                    <div class="flex justify-center items-center w-full">
                        <p class="text-gray-600">Belum ada postingan yang disukai.</p>
                    </div>
                @else
                    <div class="grid grid-cols-3 gap-2 w-full">
                        @foreach ($likedPosts as $post)
                            <a href="{{ route('posts.show', $post->id) }}" class="relative group">
                                <img src="{{ asset('storage/' . $post->media->first()->path) }}" alt="Post Image"
                                    class="w-full h-72 rounded-lg object-cover border-2 border-gray-900">
                            </a>
                        @endforeach
                    </div>
                @endif
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
                                        <button type="submit"
                                            class="text-white bg-red-500 px-4 py-2 rounded text-sm">Hapus
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
