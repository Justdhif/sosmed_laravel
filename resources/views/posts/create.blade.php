@extends('layouts.app')

@section('title', 'Buat Postingan Baru')

@section('content')
    <div class="mx-auto max-w-4xl p-6">
        <div class="bg-gradient-to-r from-purple-500 to-blue-500 text-white text-center py-6 rounded-t-lg">
            <h2 class="text-3xl font-bold">Buat Postingan Baru</h2>
            <p class="text-sm mt-1">Bagikan konten menarik dengan gambar atau video</p>
        </div>

        <div class="bg-white shadow-lg rounded-b-lg p-6">
            <div class="flex">
                <button id="btn-image"
                    class="w-1/2 py-3 rounded-t-lg flex items-center justify-center gap-2 bg-gray-100 text-gray-700 active-tab">
                    <span class="text-lg">🖼️</span> Post dengan Gambar
                </button>
                <button id="btn-video"
                    class="w-1/2 py-3 rounded-t-lg flex items-center justify-center gap-2 bg-white text-gray-500 hover:bg-gray-200">
                    <span class="text-lg">📹</span> Post dengan Video
                </button>
            </div>

            {{-- Form untuk postingan gambar --}}
            <form id="form-image" action="{{ route('posts.storeImage') }}" method="POST" enctype="multipart/form-data"
                class="mt-6">
                @csrf
                <label class="block text-sm font-medium text-gray-700">Judul</label>
                <input type="text" name="title"
                    class="w-full mt-1 p-3 border rounded-lg focus:ring focus:ring-blue-200"
                    placeholder="Masukkan judul postingan...">

                <label class="block text-sm font-medium text-gray-700 mt-4">Deskripsi</label>
                <textarea name="description" class="w-full mt-1 p-3 border rounded-lg focus:ring focus:ring-blue-200"
                    placeholder="Tulis deskripsi postingan..."></textarea>

                <label class="block text-sm font-medium text-gray-700 mt-4">Kategori</label>
                <select name="category_id" class="w-full mt-1 p-3 border rounded-lg focus:ring focus:ring-blue-200">
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>

                <label class="block text-sm font-medium text-gray-700 mt-4">Gambar</label>
                <input type="file" name="image" class="w-full p-2 border rounded-lg">

                <button type="submit" class="w-full bg-black text-white py-3 mt-6 rounded-lg">Posting</button>
            </form>

            {{-- Form untuk postingan video --}}
            <form id="form-video" action="{{ route('posts.storeVideo') }}" method="POST" class="mt-6 hidden">
                @csrf
                <label class="block text-sm font-medium text-gray-700">Judul</label>
                <input type="text" name="title"
                    class="w-full mt-1 p-3 border rounded-lg focus:ring focus:ring-blue-200"
                    placeholder="Masukkan judul postingan...">

                <label class="block text-sm font-medium text-gray-700 mt-4">Deskripsi</label>
                <textarea name="description" class="w-full mt-1 p-3 border rounded-lg focus:ring focus:ring-blue-200"
                    placeholder="Tulis deskripsi postingan..."></textarea>

                <label class="block text-sm font-medium text-gray-700 mt-4">Kategori</label>
                <select name="category_id" class="w-full mt-1 p-3 border rounded-lg focus:ring focus:ring-blue-200">
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>

                <label class="block text-sm font-medium text-gray-700 mt-4">YouTube Video ID / URL</label>
                <input type="text" name="youtube_video_id"
                    class="w-full mt-1 p-3 border rounded-lg focus:ring focus:ring-blue-200"
                    placeholder="Contoh: dQw4w9WgXcQ atau https://youtu.be/dQw4w9WgXcQ">

                <button type="submit" class="w-full bg-black text-white py-3 mt-6 rounded-lg">Posting</button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const btnImage = document.getElementById("btn-image");
            const btnVideo = document.getElementById("btn-video");
            const formImage = document.getElementById("form-image");
            const formVideo = document.getElementById("form-video");

            function setActive(tab) {
                btnImage.classList.remove("bg-gray-100", "text-gray-700", "active-tab");
                btnVideo.classList.remove("bg-gray-100", "text-gray-700", "active-tab");
                tab.classList.add("bg-gray-100", "text-gray-700", "active-tab");
            }

            btnImage.addEventListener("click", function() {
                formImage.classList.remove("hidden");
                formVideo.classList.add("hidden");
                setActive(btnImage);
            });

            btnVideo.addEventListener("click", function() {
                formVideo.classList.remove("hidden");
                formImage.classList.add("hidden");
                setActive(btnVideo);
            });
        });
    </script>

    <style>
        .active-tab {
            border-bottom: 4px solid #6366F1;
            font-weight: bold;
        }
    </style>
@endsection
