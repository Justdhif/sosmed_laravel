@extends('layouts.app')

@section('title', 'Detail Produk - ' . $product->name)

@section('content')
    <!-- Nama Produk -->
    <h1 class="text-2xl font-bold mb-2">{{ $product->name }}</h1>

    <!-- Gambar Produk -->
    <img src="{{ asset('storage/' . $product->image_path) }}" class="w-full h-[32rem] object-cover rounded-lg mb-4">

    <!-- Harga dan Deskripsi -->
    <p class="text-gray-600 text-lg">Harga: <strong>Rp{{ number_format($product->price, 0, ',', '.') }}</strong></p>
    <p class="text-gray-700 font-bold my-4">{{ $product->description }}</p>

    <form action="{{ route('wishlist.add', $product->id) }}" method="POST" class="inline">
        @csrf
        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">❤️ Tambah ke Wishlist</button>
    </form>

    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="inline">
        @csrf
        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">🛒 Tambah ke Keranjang</button>
    </form>

    @if (Auth::id() === $product->shopProfile->user_id)
        <div class="mt-4 flex space-x-2">
            <a href="{{ route('products.edit', $product->id) }}" class="bg-blue-500 text-white px-4 py-2 rounded">Edit</a>
        </div>
    @endif

    <!-- Rata-rata Rating -->
    <div class="mt-4">
        <h2 class="text-lg font-semibold">Rata-rata Rating:
            <span class="text-yellow-500">
                {{ number_format($product->averageRating(), 1) }} ⭐
            </span>
        </h2>
    </div>

    <!-- Formulir Ulasan -->
    @if (auth()->check())
        <form action="{{ route('rating.store', $product->id) }}" method="POST" enctype="multipart/form-data"
            class="mt-4">
            @csrf
            <label class="block font-semibold">Beri Rating:</label>
            <select name="rating" class="border p-2 rounded">
                <option value="1">1 - Buruk</option>
                <option value="2">2 - Cukup</option>
                <option value="3">3 - Bagus</option>
                <option value="4">4 - Sangat Bagus</option>
                <option value="5">5 - Luar Biasa</option>
            </select>
            <textarea name="review" class="border p-2 w-full mt-2" placeholder="Tulis ulasan (opsional)"></textarea>

            <!-- Input Upload Gambar Multiple -->
            <label class="block mt-2">Upload Foto Ulasan (Bisa lebih dari satu):</label>
            <input type="file" name="images[]" accept="image/*" class="border p-2 rounded" multiple>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 mt-2 rounded">Kirim</button>
        </form>
    @else
        <p class="text-gray-500 mt-4">Silakan <a href="{{ route('login') }}" class="text-blue-500">login</a> untuk
            memberi rating.</p>
    @endif

    <!-- Daftar Ulasan -->
    <h3 class="mt-6 text-lg font-semibold">Ulasan Pengguna</h3>
    @if ($product->ratings->isEmpty())
        <p class="text-gray-600">Belum ada ulasan.</p>
    @else
        @foreach ($product->ratings as $rating)
            <div class="border-b py-4">
                <p class="text-gray-600">{{ $rating->created_at->diffForHumans() }}</p>
                <div class="flex items-center">
                    <img src="{{ asset('storage/' . $rating->user->profile_picture) }}" class="w-10 h-10 rounded-full mr-2"
                        alt="">
                    <strong>{{ $rating->user->name }}</strong> -
                    <span class="text-yellow-500">{{ $rating->rating }} ⭐</span>
                </div>
                <p class="text-gray-600">{{ $rating->review }}</p>

                <!-- Tampilkan Gambar Ulasan -->
                @if ($rating->images->isNotEmpty())
                    <div class="grid grid-cols-3 gap-2 mt-2">
                        @foreach ($rating->images as $image)
                            <img src="{{ asset('storage/' . $image->image_path) }}"
                                class="w-full h-24 object-cover rounded-lg" alt="Foto Ulasan">
                        @endforeach
                    </div>
                @endif

                <!-- Hapus Ulasan (hanya user yang memberi ulasan) -->
                @if (auth()->id() == $rating->user_id)
                    <form action="{{ route('rating.destroy', $rating->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500">Hapus Ulasan</button>
                    </form>
                @endif

                <!-- Respon Ulasan (Hanya Pemilik Toko) -->
                @if (auth()->id() == $product->shopProfile->user_id)
                    <form action="{{ route('rating.respond', $rating->id) }}" method="POST" class="mt-2">
                        @csrf
                        <textarea name="response" class="border p-2 w-full" placeholder="Tulis respon"></textarea>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 mt-2 rounded">Kirim
                            Respon</button>
                    </form>
                @endif

                <!-- Tampilkan Respon dari Pemilik Toko -->
                @if ($rating->response)
                    <div class="bg-gray-100 p-2 mt-2 rounded">
                        <strong>Respon Toko:</strong>
                        <p>{{ $rating->response }}</p>
                    </div>
                @endif
            </div>
        @endforeach
    @endif
@endsection
