@extends('layouts.app')

@section('title', 'Edit Profil Toko')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Edit Profil Toko</h1>

        <form action="{{ route('shop.profile.update', $shop->id) }}" method="POST">
            @csrf
            @method('PUT')

            <label class="block mb-2">Nama Toko</label>
            <input type="text" name="name" value="{{ $shop->name }}" class="border px-4 py-2 rounded w-full mb-4">

            <label class="block mb-2">Deskripsi</label>
            <textarea name="description" class="border px-4 py-2 rounded w-full mb-4">{{ $shop->description }}</textarea>

            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Update</button>
        </form>
    </div>
@endsection
