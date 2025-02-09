@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-5">❤️ Wishlist</h1>

    @if ($wishlist->isEmpty())
        <p class="text-gray-500">Belum ada produk di wishlist.</p>
    @else
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach ($wishlist as $item)
                <div class="border p-4 rounded shadow-lg">
                    <img src="{{ asset('storage/' . $item->product->image_path) }}" alt="{{ $item->product->name }}"
                        class="w-full h-40 object-cover rounded">
                    <h2 class="text-lg font-semibold mt-2">{{ $item->product->name }}</h2>
                    <p class="text-gray-500">Rp {{ number_format($item->product->price, 0, ',', '.') }}</p>

                    <div class="mt-3 flex space-x-2">
                        <form action="{{ route('wishlist.remove', $item->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Hapus</button>
                        </form>
                        <form action="{{ route('cart.add', $item->product->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Tambah ke
                                Keranjang</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
