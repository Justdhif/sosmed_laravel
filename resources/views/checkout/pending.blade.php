@extends('layouts.app')

@section('content')
<div class="container text-center">
    <h2>Pembayaran Pending!</h2>
    <p>Pembayaran Anda masih dalam proses. Silakan cek status transaksi Anda nanti.</p>
    <a href="{{ route('home') }}" class="btn btn-primary">Kembali ke Beranda</a>
</div>
@endsection
