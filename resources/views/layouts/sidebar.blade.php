<style>
    /* CSS untuk sidebar yang collapsed */
    #sidebar.collapsed {
        width: 100px;
        /* Lebar sidebar ketika collapsed */
    }

    #sidebar.collapsed .logo-text,
    #sidebar.collapsed .menu-text {
        display: none;
        /* Sembunyikan teks ketika sidebar collapsed */
    }

    #sidebar.collapsed .fa-bars {
        transform: rotate(90deg);
        /* Rotate icon ketika collapsed */
    }

    #sidebar.collapsed .flex.items-center.gap-6 {
        justify-content: start;
        /* Pusatkan icon ketika collapsed */
    }

    #sidebar.collapsed .border-b {
        text-align: center;
        /* Pusatkan teks kategori ketika collapsed */
    }

    #sidebar.collapsed .border-b .menu-text {
        display: none;
        /* Sembunyikan teks kategori ketika collapsed */
    }

    /* CSS untuk gambar logo dan profil */
    #sidebar .logo img {
        width: 40px;
        /* Gambar mengisi container */
        height: 40px;
        /* Tinggi disesuaikan agar tidak gepeng */
        object-fit: cover;
        /* Memastikan gambar tidak terdistorsi */
        border-radius: 50%;
        /* Untuk gambar profil (opsional) */
    }

    #sidebar .profile-picture img {
        width: 30px;
        /* Ukuran tetap ketika collapsed */
        height: 30px;
        /* Ukuran tetap ketika collapsed */
        object-fit: cover;
    }

    /* Ketika sidebar collapsed */
    #sidebar.collapsed .logo img,
    #sidebar.collapsed .profile-picture img {
        width: 40px;
        /* Ukuran tetap ketika collapsed */
        height: 40px;
        /* Ukuran tetap ketika collapsed */
        object-fit: cover
    }
</style>

<aside id="sidebar"
    class="w-64 h-full p-4 px-3 bg-white flex justify-start flex-col z-40 rounded-lg overflow-y-scroll transition-all duration-300 ease-in-out">

    <!-- Menu -->
    <nav class="flex flex-col gap-2">
        <!-- Logo -->
        <div class="logo flex items-center gap-5 py-3 px-3 mb-5">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-10 h-10">
            <span class="logo-text font-bold text-lg">Justnews</span>
        </div>

        <!-- Toggle Button -->
        <button id="toggleSidebar" class="p-2 mb-4 bg-gray-200 rounded-full hover:bg-gray-300">
            <i class="fa-solid fa-bars"></i>
        </button>

        <!-- Menu Items -->
        <a href="{{ route('home') }}"
            class="flex items-center gap-6 py-3 px-4 rounded-md transition-all duration-500 ease-out hover:bg-slate-500 hover:bg-opacity-20 @if (Route::is('home')) bg-slate-500 bg-opacity-20 @endif">
            <i class="fa-solid fa-house text-2xl {{ Route::is('home') ? 'opacity-100' : 'opacity-70' }}"></i>
            <span class="menu-text font-bold text-lg">Beranda</span>
        </a>
        <a href="{{ route('search.images') }}"
            class="flex items-center gap-6 py-3 px-4 rounded-md transition-all duration-500 ease-out hover:bg-slate-500 hover:bg-opacity-20 @if (Route::is('search.images')) bg-slate-500 bg-opacity-20 @endif">
            <i class="fa-solid fa-compass text-[25px] {{ Route::is('search.images') ? 'opacity-100' : 'opacity-70' }}"></i>
            <span class="menu-text font-bold text-lg">Jelajahi</span>
        </a>
        <a href="{{ route('posts.create') }}"
            class="flex items-center gap-6 py-3 px-4 rounded-md transition-all duration-500 ease-out hover:bg-slate-500 hover:bg-opacity-20 @if (Route::is('posts.create')) bg-slate-500 bg-opacity-20 @endif">
            <i class="fa-solid fa-plus-square text-[26px] {{ Route::is('posts.create') ? 'opacity-100' : 'opacity-70' }} ml-[2px]"></i>
            <span class="menu-text font-bold text-lg">Buat</span>
        </a>

        {{-- Menu Video --}}
        <div class="border-b px-6 py-3 border-gray-300 text-gray-500 font-bold text-lg mb-3">
            <span class="menu-text">Video</span>
        </div>
        <a href="{{ route('search.videos') }}"
            class="flex items-center gap-6 py-3 px-4 rounded-md transition-all duration-500 ease-out hover:bg-slate-500 hover:bg-opacity-20 @if (Route::is('search.videos') || Route::is('video.show')) bg-slate-500 bg-opacity-20 @endif">
            <i class="fa-solid fa-video text-2xl {{ Route::is('search.videos') || Route::is('video.show') ? 'opacity-100' : 'opacity-70' }}"></i>
            <span class="menu-text font-bold text-lg">Video</span>
        </a>

        {{-- Menu Shop --}}
        <div class="border-b px-6 py-3 border-gray-300 text-gray-500 font-bold text-lg mb-3">
            <span class="menu-text">Shop</span>
        </div>
        <a href="{{ route('products.index') }}"
            class="flex items-center gap-6 py-3 px-4 rounded-md transition-all duration-500 ease-out hover:bg-slate-500 hover:bg-opacity-20 @if (Route::is('products.index')) bg-slate-500 bg-opacity-20 @endif">
            <i class="fa-solid fa-basket-shopping text-2xl {{ Route::is('products.index') ? 'opacity-100' : 'opacity-70' }}"></i>
            <span class="menu-text font-bold text-lg">Shop</span>
        </a>
        <a href="{{ route('wishlist.index') }}"
            class="flex items-center gap-6 py-3 px-4 rounded-md transition-all duration-500 ease-out hover:bg-slate-500 hover:bg-opacity-20 @if (Route::is('wishlist.index')) bg-slate-500 bg-opacity-20 @endif">
            <i class="fa-solid fa-heart text-2xl {{ Route::is('wishlist.index') ? 'opacity-100' : 'opacity-70' }}"></i>
            <span class="menu-text font-bold text-lg">Wishlist</span>
        </a>
        <a href="{{ route('cart.index') }}"
            class="flex items-center gap-6 py-3 px-4 rounded-md transition-all duration-500 ease-out hover:bg-slate-500 hover:bg-opacity-20 @if (Route::is('cart.index')) bg-slate-500 bg-opacity-20 @endif">
            <i class="fa-solid fa-cart-shopping text-2xl {{ Route::is('cart.index') ? 'opacity-100' : 'opacity-70' }}"></i>
            <span class="menu-text font-bold text-lg">Cart</span>
        </a>

        {{-- More --}}
        <div class="border-b px-6 py-3 border-gray-300 text-gray-500 font-bold text-lg mb-3">
            <span class="menu-text">More</span>
        </div>
        <a href="{{ route('messages.index') }}"
            class="flex items-center gap-6 py-3 px-4 rounded-md transition-all duration-500 ease-out {{ Route::is('messages.index') || Route::is('messages.show') ? 'bg-slate-500 bg-opacity-20' : '' }} hover:bg-slate-500 hover:bg-opacity-20">
            <i class="fa-solid fa-envelope text-2xl {{ Route::is('messages.index') || Route::is('messages.show') ? 'opacity-100' : 'opacity-70' }}"></i>
            <span class="menu-text font-bold text-lg">Message</span>
        </a>
        <!-- Foto Profil -->
        <a href="{{ route('profile.show', ['name' => auth()->user()->name]) }}"
            class="profile-picture flex items-center gap-6 py-3 px-4 rounded-md transition-all duration-500 ease-out hover:bg-slate-500 hover:bg-opacity-20 @if (Route::is('profile.show')) bg-slate-500 bg-opacity-20 @endif">
            <img src="{{ filter_var(Auth::user()->profile_picture, FILTER_VALIDATE_URL) ? Auth::user()->profile_picture : asset('storage/' . (Auth::user()->profile_picture ?? 'profile_pictures/default.jpg')) }}"
                alt="Profile Picture" class="w-4 h-4 rounded-full object-cover">
            <span class="menu-text font-bold text-lg">Profile</span>
        </a>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="w-full flex items-center gap-6 py-3 px-4 rounded-md text-red-600 transition-all duration-500 ease-out hover:bg-red-300">
                <i class="fa-solid fa-right-from-bracket text-xl"></i>
                <span class="menu-text font-bold text-lg">Logout</span>
            </button>
        </form>
    </nav>
</aside>

<script>
    document.getElementById('toggleSidebar').addEventListener('click', function() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('collapsed');
    });
</script>
