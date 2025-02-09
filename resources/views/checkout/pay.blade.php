@extends('layouts.app')

@section('content')
<div class="container text-center">
    <h2>Pembayaran</h2>
    <p>Silakan lakukan pembayaran dengan Midtrans.</p>

    <button id="pay-button" class="btn btn-success">Bayar Sekarang</button>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script>
        document.getElementById('pay-button').onclick = function () {
            snap.pay("{{ $token }}", {
                onSuccess: function(result) {
                    window.location.href = "{{ route('checkout.success') }}";
                },
                onPending: function(result) {
                    window.location.href = "{{ route('checkout.pending') }}";
                },
                onError: function(result) {
                    window.location.href = "{{ route('checkout.failed') }}";
                }
            });
        };
    </script>
</div>
@endsection
