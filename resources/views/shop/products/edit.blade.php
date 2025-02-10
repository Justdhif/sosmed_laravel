@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Edit Produk</h1>

        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <label class="block mb-2">Nama Produk</label>
            <input type="text" name="name" value="{{ $product->name }}" class="border px-4 py-2 rounded w-full mb-4">

            <label class="block mb-2">Deskripsi</label>
            <textarea name="description" class="border px-4 py-2 rounded w-full mb-4">{{ $product->description }}</textarea>

            <label class="block mb-2">Harga</label>
            <input type="number" name="price" value="{{ $product->price }}" class="border px-4 py-2 rounded w-full mb-4">

            <label class="block mb-2">Gambar Produk</label>
            <input type="file" name="image" class="border px-4 py-2 rounded w-full mb-4">

            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Update</button>
        </form>
    </div>
@endsection
