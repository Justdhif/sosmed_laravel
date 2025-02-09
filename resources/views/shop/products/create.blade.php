@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg">
        <h2 class="text-2xl font-bold mb-6">Tambah Produk</h2>

        @if (session('error'))
            <div class="bg-red-500 text-white p-3 rounded-md mb-4">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Nama Produk -->
            <div class="mb-4">
                <label for="name" class="block font-semibold">Nama Produk</label>
                <input type="text" name="name" id="name" required
                    class="w-full p-2 border border-gray-300 rounded mt-1">
                @error('name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Deskripsi -->
            <div class="mb-4">
                <label for="description" class="block font-semibold">Deskripsi</label>
                <textarea name="description" id="description" required rows="3"
                    class="w-full p-2 border border-gray-300 rounded mt-1"></textarea>
                @error('description')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Harga -->
            <div class="mb-4">
                <label for="price" class="block font-semibold">Harga (Rp)</label>
                <input type="number" name="price" id="price" required
                    class="w-full p-2 border border-gray-300 rounded mt-1">
                @error('price')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Stok -->
            <div class="mb-4">
                <label for="stock" class="block font-semibold">Stok</label>
                <input type="number" name="stock" id="stock" required
                    class="w-full p-2 border border-gray-300 rounded mt-1">
                @error('stock')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Gambar Produk -->
            <div class="mb-4">
                <label for="image" class="block font-semibold">Gambar Produk</label>
                <input type="file" name="image" id="image" accept="image/*" required
                    class="w-full p-2 border border-gray-300 rounded mt-1">
                @error('image')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Submit Button -->
            <button type="submit"
                class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">
                Tambah Produk
            </button>
        </form>
    </div>
@endsection
