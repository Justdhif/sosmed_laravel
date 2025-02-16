@extends('layouts.app')

@section('title', 'Edit Profil - Social Media ' . $user->name)

@section('content')
    <form action="{{ route('profile.update', Auth::user()->id) }}" method="POST" enctype="multipart/form-data"
        class="w-full h-full bg-white p-3 rounded-lg flex flex-col">
        @csrf
        @method('PUT')

        <h2 class="text-2xl font-semibold mb-4">Edit Profil</h2>

        @if (session('success'))
            <div class="mb-4 text-green-600">{{ session('success') }}</div>
        @endif

        <!-- Background Image -->
        <div class="mb-4">
            <label class="block text-xl font-medium text-gray-700 mb-4">Background Profil</label>
            <div class="flex flex-col items-start">
                <img src="{{ asset('storage/' . ($user->background_image ?? 'profile_backgrounds/default-bg.jpg')) }}"
                    class="w-full h-64 object-cover rounded">
                <input type="file" name="background_image" accept="image/*" class="mt-3 block">
            </div>
            @error('background_image')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex gap-4">
            <!-- Foto Profil -->
            <div class="mb-4">
                <label class="block text-xl font-medium text-gray-700 mb-4">Foto Profil</label>
                <div class="flex flex-col items-start">
                    <img src="{{ filter_var($user->profile_picture, FILTER_VALIDATE_URL) ? $user->profile_picture : asset('storage/' . ($user->profile_picture ?? 'profile_pictures/default.jpg')) }}"
                        class="w-24 h-24 rounded-full object-cover">
                </div>
                @error('profile_picture')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-12">
                <!-- Nama -->
                <div class="mb-2">
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                        class="w-full px-4 py-2 border-2 rounded-lg focus:border-blue-500 focus:outline-none">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex gap-6">
                    <span class="text-lg font-bold"><strong>{{ $user->posts->count() }}</strong> Post</span>
                    <span class="text-lg font-bold"><strong>{{ $user->followers->count() }}</strong> Followers</span>
                    <span class="text-lg font-bold"><strong>{{ $user->following->count() }}</strong> Following</span>
                </div>
            </div>
        </div>

        <input type="file" name="profile_picture" accept="image/*">

        <div class="mt-5 border-b-2 border-gray-300"></div>

        {{-- Bio --}}
        <div class="my-4">
            <label for="bio" class="block text-xl font-medium text-gray-700 mb-3">Bio</label>
            <textarea id="bio" name="bio" rows="3"
                class="w-full px-4 py-2 border-2 rounded-lg focus:border-blue-500 focus:outline-none resize-none">{{ old('bio', $user->bio) }}</textarea>
            @error('bio')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Button submit --}}
        <div class="flex justify-end mb-10">
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg shadow hover:bg-blue-600">Simpan
                Perubahan</button>
        </div>
    </form>
@endsection
