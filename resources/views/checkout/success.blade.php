@extends('layouts.app')

@section('content')
<div class="container text-center">
    <h2>Pembayaran Berhasil!</h2>
    <p>Terima kasih atas pesanan Anda. Pesanan Anda sedang diproses.</p>
    <a href="{{ route('home') }}" class="btn btn-primary">Kembali ke Beranda</a>
</div>
@endsection
