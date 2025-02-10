@extends('layouts.app')

@section('title', 'Produk Toko')

@section('content')
    <div class="container mx-auto px-4">
        <form action="{{ route('products.search') }}" method="GET" class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center">
                <input type="text" name="query" placeholder="Cari produk..."
                    class="border border-gray-300 px-4 py-2 rounded w-full">
                <button type="submit"
                    class="bg-blue-500 text-white px-4 py-2 rounded mt-2 md:mt-0 md:ml-2 hover:bg-blue-600 transition text-md font-bold">Cari</button>

                <!-- Ikon Cart dan Wishlist -->
                <div class="flex items-center mt-2 md:mt-0 md:ml-2">
                    <a href="{{ route('cart.index') }}" class="relative bg-blue-500 text-white px-4 py-2 rounded mt-2 md:mt-0 md:ml-2 hover:bg-blue-600 transition"
                        title="Keranjang Belanja">
                        <i class="fa-solid fa-cart-shopping text-md"></i>
                        @if ($cartCount > 0)
                            <span
                                class="absolute top-0 right-0 bg-red-500 text-white rounded-full w-4 h-4 flex items-center justify-center">{{ $cartCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('wishlist.index') }}" class="relative bg-red-500 text-white px-4 py-2 rounded mt-2 md:mt-0 md:ml-2 hover:bg-red-600 transition"
                        title="Daftar Keinginan">
                        <i class="fa-solid fa-heart text-md"></i>
                        @if ($wishlistCount > 0)
                            <span
                                class="absolute top-0 right-0 bg-red-500 text-white rounded-full w-4 h-4 flex items-center justify-center">{{ $wishlistCount }}</span>
                        @endif
                    </a>
                </div>
            </div>
        </form>

        @if (isset($query))
            <h2 class="text-2xl font-semibold mb-4">Hasil pencarian untuk: "<span
                    class="text-blue-500">{{ $query }}</span>"</h2>
        @else
            <h2 class="text-2xl font-semibold mb-4">Semua Produk</h2>
        @endif

        <!-- Jika ada produk -->
        @if ($products->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach ($products as $product)
                    <div class="border rounded-lg shadow-lg p-4 transition-transform transform hover:scale-105">
                        <!-- Gambar Produk -->
                        <img src="{{ asset('storage/' . $product->image_path) }}"
                            class="w-full h-40 object-cover rounded-md mb-3">

                        <!-- Nama Produk -->
                        <h2 class="text-lg font-semibold text-gray-800">{{ $product->name }}</h2>

                        <!-- Deskripsi Singkat -->
                        <p class="text-sm text-gray-600 truncate">{{ $product->description }}</p>

                        <!-- Harga -->
                        <p class="text-md font-bold mt-2 text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}
                        </p>

                        <!-- Tombol Detail -->
                        <a href="{{ route('products.show', $product->id) }}"
                            class="block mt-3 px-4 py-2 bg-blue-500 text-white text-center rounded-md hover:bg-blue-600 transition">
                            Lihat Detail
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Jika tidak ada produk -->
            <p class="text-gray-600 text-center mt-6">Belum ada produk yang tersedia.</p>
        @endif
    </div>
@endsection
