@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold mb-4">Edit Profil</h2>

        @if (session('success'))
            <div class="mb-4 text-green-600">{{ session('success') }}</div>
        @endif

        <form action="{{ route('profile.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Nama -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                    class="mt-1 block w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Bio -->
            <div class="mb-4">
                <label for="bio" class="block text-sm font-medium text-gray-700">Bio</label>
                <textarea id="bio" name="bio" rows="3"
                    class="mt-1 block w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300">{{ old('bio', $user->bio) }}</textarea>
                @error('bio')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Foto Profil -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Foto Profil</label>
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('storage/' . $user->profile_picture) }}" class="w-16 h-16 rounded-full object-cover">
                    <input type="file" name="profile_picture" accept="image/*" class="mt-1 block">
                </div>
                @error('profile_picture')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Background Image -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Background Profil</label>
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('storage/' . $user->background_image) }}" class="w-32 h-16 object-cover rounded">
                    <input type="file" name="background_image" accept="image/*" class="mt-1 block">
                </div>
                @error('background_image')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tombol Simpan -->
            <div class="flex justify-end">
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg shadow hover:bg-blue-600">Simpan
                    Perubahan</button>
            </div>
        </form>
    </div>
@endsection
