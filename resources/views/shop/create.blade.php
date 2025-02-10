@extends('layouts.app')

@section('title', 'Buat Profil Toko')

@section('content')
    <div class="container">
        <h2 class="text-xl font-bold mb-4">Buat Profil Toko</h2>
        <form action="{{ route('shop.profile.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="name">Nama Toko:</label>
                <input type="text" name="name" class="border p-2 w-full" required>
            </div>
            <div class="mb-4">
                <label for="description">Deskripsi:</label>
                <textarea name="description" class="border p-2 w-full" required></textarea>
            </div>
            <div class="mb-4">
                <label for="image">Upload Gambar (Opsional):</label>
                <input type="file" name="image" class="border p-2 w-full">
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Lanjut ke Verifikasi OTP</button>
        </form>
    </div>
@endsection
