@extends('layouts.app')

@section('title', 'Chat with ' . $receiver->name)

@section('content')

    <div class="w-full h-screen flex flex-col border rounded-lg shadow-md">
        <div class="p-4 flex items-center justify-between border-b bg-white">
            <div class="flex items-center space-x-3">
                <a href="{{ route('messages.index') }}"
                    class="flex items-center gap-3 py-3 px-4 rounded-md transition-all duration-500 ease-out hover:bg-slate-500 hover:bg-opacity-20">
                    <i class="fa-solid fa-arrow-left text-xl"></i>
                </a>
                <img src="{{ filter_var($receiver->profile_picture, FILTER_VALIDATE_URL) ? $receiver->profile_picture : asset('storage/' . ($receiver->profile_picture ?? 'profile_pictures/default.jpg')) }}"
                    class="w-10 h-10 rounded-full" alt="{{ $receiver->name }}">
                <h2 class="text-lg font-semibold">{{ $receiver->name }}</h2>
            </div>
        </div>

        <div id="chat-container" class="flex-1 p-4 space-y-2 overflow-y-auto bg-gray-100">
            @foreach ($messages as $message)
                @if ($message->sender_id == auth()->id())
                    {{-- Chat Bubble (Pengguna Sendiri) --}}
                    <div class="flex justify-end">
                        <div class="bg-blue-500 text-white p-3 rounded-lg max-w-xs">
                            <p>{{ $message->message }}</p>
                            <small class="text-xs opacity-75">{{ $message->created_at->format('H:i') }}</small>
                        </div>
                    </div>
                @else
                    <div class="flex items-end space-x-2">
                        <img src="{{ filter_var($receiver->profile_picture, FILTER_VALIDATE_URL) ? $receiver->profile_picture : asset('storage/' . ($receiver->profile_picture ?? 'profile_pictures/default.jpg')) }}"
                            class="w-8 h-8 rounded-full" alt="{{ $receiver->name }}">
                        <div class="bg-gray-200 p-3 rounded-lg max-w-xs">
                            <p>{{ $message->message }}</p>
                            <small class="text-xs opacity-75">{{ $message->created_at->format('H:i') }}</small>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <div class="p-4 bg-white shadow-md w-full">
            <form action="{{ route('messages.store', $receiver->id) }}" method="POST" class="flex space-x-2">
                @csrf
                <input type="text" name="content" class="flex-1 p-2 border rounded-lg focus:outline-none"
                    placeholder="Ketik pesan..." required>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Kirim</button>
            </form>
        </div>
    </div>

    {{-- Auto Scroll ke Bawah Saat Ada Pesan Baru --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let chatContainer = document.getElementById("chat-container");
            chatContainer.scrollTop = chatContainer.scrollHeight;
        });
    </script>
@endsection
