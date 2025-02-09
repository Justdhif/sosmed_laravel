@extends('layouts.app')

@section('content')
    <div class="p-4 bg-white rounded-lg">
        <h2 class="text-xl font-semibold mb-4">Profil Toko</h2>

        @if ($shopProfile)
            <div class="flex items-center gap-6">
                <img src="{{ asset('storage/' . ($shopProfile->image_path ?? 'shop_profiles/default-shop.jpg')) }}"
                    alt="{{ $shopProfile->name }}"
                    class="w-24 h-24 rounded-full object-cover border-2 border-gray-800">

                <div>
                    <h3 class="text-lg font-bold">{{ $shopProfile->name }}</h3>
                    <p>{{ $shopProfile->description }}</p>

                    @if (auth()->id() === $shopProfile->user_id)
                        <a href=""
                            class="px-4 py-2 mt-2 bg-gray-200 rounded text-sm font-semibold">Edit Toko</a>
                    @endif
                </div>
            </div>
        @else
            <div class="text-gray-600">
                <p class="mb-5">Anda belum memiliki profil toko.</p>
                <a href="{{ route('shop.profile.create') }}"
                    class="px-4 py-2 bg-green-500 text-white rounded text-sm font-semibold">Buat Profil Toko</a>
            </div>
        @endif
    </div>
@endsection
