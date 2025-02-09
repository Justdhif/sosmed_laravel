@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Checkout</h2>
    <p>Total Pembayaran: <strong>Rp {{ number_format($totalPrice, 0, ',', '.') }}</strong></p>

    <form action="{{ route('checkout.process') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary">Bayar Sekarang</button>
    </form>
</div>
@endsection
