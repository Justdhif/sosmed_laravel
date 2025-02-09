@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-5">
        <h1 class="text-2xl font-bold mb-5">🛒 Keranjang Belanja</h1>

        @if ($cart->isEmpty())
            <p class="text-gray-500">Keranjang masih kosong.</p>
        @else
            <form id="checkout-form" action="{{ route('checkout.process') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Bagian Kiri: Daftar Produk -->
                    <div class="md:col-span-2 space-y-4">
                        @foreach ($cart as $item)
                            <div class="border p-4 rounded shadow-lg flex items-center">
                                <input type="checkbox" name="selected_items[]" value="{{ $item->id }}"
                                    class="select-item mr-3 w-5 h-5">

                                <img src="{{ asset('storage/' . $item->product->image_path) }}"
                                    alt="{{ $item->product->name }}" class="w-24 h-24 object-cover rounded">

                                <div class="ml-4 flex-1">
                                    <h2 class="text-lg font-semibold">{{ $item->product->name }}</h2>
                                    <p class="text-gray-500">Rp {{ number_format($item->product->price, 0, ',', '.') }}</p>

                                    <input type="hidden" class="item-price"
                                        value="{{ $item->product->price * $item->quantity }}">

                                    <form action="{{ route('cart.update', $item->id) }}" method="POST"
                                        class="mt-2 flex items-center space-x-2">
                                        @csrf
                                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1"
                                            class="border px-2 py-1 w-16 text-center">
                                        <button type="submit"
                                            class="bg-blue-500 text-white px-3 py-1 rounded">Update</button>
                                    </form>

                                    <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="mt-2">
                                        @csrf
                                        <button type="submit"
                                            class="bg-red-500 text-white px-3 py-1 rounded">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Bagian Kanan: Total Harga dan Checkout -->
                    <div class="bg-white p-6 rounded shadow-lg">
                        <h2 class="text-xl font-semibold mb-4">🧾 Ringkasan Belanja</h2>

                        <div class="flex justify-between text-lg font-semibold border-b pb-2">
                            <span>Total Harga:</span>
                            <span id="total-price">Rp 0</span>
                        </div>

                        <button type="submit" id="checkout-button"
                            class="bg-green-500 text-white text-center block w-full py-3 rounded mt-4"
                            disabled>Checkout</button>
                    </div>
                </div>
            </form>
        @endif
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const checkboxes = document.querySelectorAll(".select-item");
            const totalPriceElement = document.getElementById("total-price");
            const checkoutButton = document.getElementById("checkout-button");

            function updateTotalPrice() {
                let total = 0;
                checkboxes.forEach((checkbox, index) => {
                    if (checkbox.checked) {
                        total += parseInt(document.querySelectorAll(".item-price")[index].value);
                    }
                });
                totalPriceElement.textContent = `Rp ${total.toLocaleString('id-ID')}`;
                checkoutButton.disabled = total === 0;
            }

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener("change", updateTotalPrice);
            });

            updateTotalPrice();
        });
    </script>
@endsection
