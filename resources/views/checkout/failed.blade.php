@extends('layouts.app')

@section('content')
<div class="container text-center">
    <h2>Pembayaran Gagal!</h2>
    <p>Maaf, transaksi Anda gagal atau dibatalkan.</p>
    <a href="{{ route('checkout.show') }}" class="btn btn-warning">Coba Lagi</a>
</div>
@endsection
