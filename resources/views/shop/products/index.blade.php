@extends('layouts.app')

@section('content')
    <form action="{{ route('products.search') }}" method="GET" class="mb-4">
        <input type="text" name="query" placeholder="Cari produk..." class="border px-4 py-2 rounded w-full">
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded mt-2">Cari</button>
    </form>

    @if (isset($query))
        <h2 class="text-xl font-semibold mb-4">Hasil pencarian untuk: "{{ $query }}"</h2>
    @endif

    <h1 class="text-2xl font-bold mb-6">Semua Produk</h1>

    <!-- Jika ada produk -->
    @if ($products->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($products as $product)
                <div class="border rounded-lg shadow-lg p-4">
                    <!-- Gambar Produk -->
                    <img src="{{ asset('storage/' . $product->image_path) }}"
                        class="w-full h-40 object-cover rounded-md mb-3">

                    <!-- Nama Produk -->
                    <h2 class="text-lg font-semibold">{{ $product->name }}</h2>

                    <!-- Deskripsi Singkat -->
                    <p class="text-sm text-gray-600 truncate">{{ $product->description }}</p>

                    <!-- Harga -->
                    <p class="text-md font-bold mt-2">Rp {{ number_format($product->price, 0, ',', '.') }}</p>

                    <!-- Tombol Detail -->
                    <a href="{{ route('products.show', $product->id) }}"
                        class="block mt-3 px-4 py-2 bg-blue-500 text-white text-center rounded-md hover:bg-blue-600">
                        Lihat Detail
                    </a>
                </div>
            @endforeach
        </div>
    @else
        <!-- Jika tidak ada produk -->
        <p class="text-gray-600">Belum ada produk yang tersedia.</p>
    @endif
@endsection
