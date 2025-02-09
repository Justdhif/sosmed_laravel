@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="text-xl font-bold mb-4">Verifikasi OTP</h2>

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <strong>Error:</strong> {{ session('error') }}
        </div>
    @endif

    <!-- Tampilkan OTP yang tersimpan di session -->
    <p class="mb-4">Kode OTP telah dikirim: <strong>{{ session('otp') }}</strong></p>

    <form action="{{ route('shop.profile.verify') }}" method="POST">
        @csrf
        <div class="flex gap-2 mb-4">
            @for ($i = 0; $i < 6; $i++)
                <input type="text" name="otp[]" maxlength="1"
                    class="otp-input w-12 h-12 text-center text-xl border rounded" required>
            @endfor
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Verifikasi</button>
    </form>
</div>
@endsection
