<aside id="sidebar"
    class="w-1/6 h-full p-4 px-3 bg-white flex justify-start flex-col z-40 rounded-lg overflow-y-scroll">
    <!-- Menu -->
    <nav class="flex flex-col">
        <!-- Logo -->
        <div class="flex items-center gap-6 py-3 px-4 mb-5">
            <i class="fa-solid fa-newspaper text-xl"></i>
            <span class="font-bold text-lg">Justnews</span>
        </div>

        <a href="{{ route('home') }}"
            class="flex items-center gap-6 py-3 px-4 rounded-md transition-all duration-500 ease-out hover:bg-slate-500 hover:bg-opacity-20 @if (Route::is('home')) bg-slate-500 bg-opacity-20 @endif">
            @if (Route::is('home'))
                <i class="fa-solid fa-house text-2xl"></i>
            @else
                <i class="fa-solid fa-house text-2xl opacity-70"></i>
            @endif
            <span class="font-bold text-lg">Beranda</span>
        </a>
        <a href="{{ route('posts.explore') }}"
            class="flex items-center gap-6 py-3 px-4 rounded-md transition-all duration-500 ease-out hover:bg-slate-500 hover:bg-opacity-20 @if (Route::is('posts.explore')) bg-slate-500 bg-opacity-20 @endif">
            @if (Route::is('posts.explore'))
                <i class="fas fa-compass text-2xl"></i>
            @else
                <i class="fa-regular fa-compass text-2xl"></i>
            @endif
            <span class="font-bold text-lg">Jelajahi</span>
        </a>
        <a href="{{ route('posts.create') }}"
            class="flex items-center gap-6 py-3 px-4 rounded-md transition-all duration-500 ease-out hover:bg-slate-500 hover:bg-opacity-20 @if (Route::is('posts.create')) bg-slate-500 bg-opacity-20 @endif">
            <i class="fa-regular fa-plus-square text-2xl"></i>
            <span class="font-bold text-lg">Buat</span>
        </a>

        {{-- Menu Video --}}
        <div class="border-b px-6 py-3 border-gray-300 text-gray-500 font-bold text-lg mb-3">
            Video
        </div>
        <a href="{{ route('youtube.index') }}"
            class="flex items-center gap-6 py-3 px-4 rounded-md transition-all duration-500 ease-out hover:bg-slate-500 hover:bg-opacity-20 @if (Route::is('youtube.index') || Route::is('video.detail')) bg-slate-500 bg-opacity-20 @endif">
            <i class="fa-solid fa-video text-2xl"></i>
            <span class="font-bold text-lg">Video</span>
        </a>
        <a href="{{ route('playlist.index') }}"
            class="flex items-center gap-6 py-3 px-4 rounded-md transition-all duration-500 ease-out hover:bg-slate-500 hover:bg-opacity-20 @if (Route::is('playlist.index') || Route::is('video.detail')) bg-slate-500 bg-opacity-20 @endif">
            <i class="fa-solid fa-list text-2xl"></i>
            <span class="font-bold text-lg">Playlist</span>
        </a>

        {{-- Menu Shop --}}
        <div class="border-b px-6 py-3 border-gray-300 text-gray-500 font-bold text-lg mb-3">
            Shop
        </div>
        <a href="{{ route('products.index') }}"
            class="flex items-center gap-6 py-3 px-4 rounded-md transition-all duration-500 ease-out hover:bg-slate-500 hover:bg-opacity-20 @if (Route::is('products.index')) bg-slate-500 bg-opacity-20 @endif">
            <i class="fa-solid fa-basket-shopping text-2xl"></i>
            <span class="font-bold text-lg">Shop</span>
        </a>
        <a href="{{ route('wishlist.index') }}"
            class="flex items-center gap-6 py-3 px-4 rounded-md transition-all duration-500 ease-out hover:bg-slate-500 hover:bg-opacity-20 @if (Route::is('wishlist.index')) bg-slate-500 bg-opacity-20 @endif">
            @if (Route::is('wishlist.index'))
                <i class="fa-solid fa-heart text-2xl"></i>
            @else
                <i class="fa-regular fa-heart text-2xl"></i>
            @endif
            <span class="font-bold text-lg">Wishlist</span>
        </a>
        <a href="{{ route('cart.index') }}"
            class="flex items-center gap-6 py-3 px-4 rounded-md transition-all duration-500 ease-out hover:bg-slate-500 hover:bg-opacity-20 @if (Route::is('cart.index')) bg-slate-500 bg-opacity-20 @endif">
            <i class="fa-solid fa-cart-shopping text-2xl"></i>
            <span class="font-bold text-lg">Cart</span>
        </a>

        {{-- More --}}
        <div class="border-b px-6 py-3 border-gray-300 text-gray-500 font-bold text-lg mb-3">
            More
        </div>
        <a href=""
            class="flex items-center gap-6 py-3 px-4 rounded-md transition-all duration-500 ease-out hover:bg-slate-500 hover:bg-opacity-20">
            <i class="fa-regular fa-envelope text-2xl"></i>
            <span class="font-bold text-lg">Pesan</span>
        </a>
        <a href="{{ route('profile.show', ['name' => auth()->user()->name]) }}"
            class="flex items-center gap-6 py-3 px-4 rounded-md transition-all duration-500 ease-out hover:bg-slate-500 hover:bg-opacity-20 @if (Route::is('profile.show')) bg-slate-500 bg-opacity-20 @endif">
            <img src="{{ asset('storage/' . (Auth::user()->profile_picture ?? 'profile_pictures/default.jpg')) }}"
                class="w-6 h-6 rounded-full object-cover">
            <span class="font-bold text-lg">Profile</span>
        </a>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="w-full flex items-center gap-6 py-3 px-4 rounded-md text-red-600 transition-all duration-500 ease-out hover:bg-red-300">
                <i class="fa-solid fa-right-from-bracket text-xl"></i>
                <span class="font-bold text-lg">Logout</span>
            </button>
        </form>
    </nav>
</aside>
