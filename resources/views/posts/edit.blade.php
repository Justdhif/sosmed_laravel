@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Edit Postingan</h1>

    <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <label class="block mb-2">Judul</label>
        <input type="text" name="title" value="{{ $post->title }}" class="border px-4 py-2 rounded w-full mb-4">

        <label class="block mb-2">Deskripsi</label>
        <textarea name="description" class="border px-4 py-2 rounded w-full mb-4">{{ $post->description }}</textarea>

        <label class="block mb-2">Gambar</label>
        <input type="file" name="image" class="border px-4 py-2 rounded w-full mb-4">

        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Update</button>
    </form>
</div>
@endsection
