@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-lg mt-10">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Create a New Post</h2>

    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="title">Judul:</label>
        <input type="text" name="title" class="w-full border rounded-md p-2" required>

        <label for="description">Deskripsi:</label>
        <textarea name="description" class="w-full border rounded-md p-2" required></textarea>

        <label for="media">Upload Gambar (Maksimal 5):</label>
        <input type="file" name="media[]" multiple accept="image/*" class="w-full border rounded-md p-2" required>

        <button type="submit" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-md">Upload</button>
    </form>
</div>
@endsection
