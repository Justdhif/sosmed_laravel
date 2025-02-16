@extends('layouts.app')

@section('title', 'Messages')

@section('content')
    <div class="flex h-full">
        <!-- Sidebar Kiri -->
        <div class="w-1/3 p-4 overflow-y-auto border-r">
            <div class="flex items-center gap-4 mb-5">
                <img src="{{ asset('images/message.webp') }}" alt="Logo" class="w-10 h-10">
                <h2 class="text-2xl font-bold">Messages</h2>
            </div>

            <!-- Search Bar -->
            <form action="{{ route('messages.index') }}" method="GET" class="flex gap-2 mb-4">
                <input type="text" name="query" placeholder="Cari User..."
                    class="p-2 border rounded-lg w-full focus:outline-none focus:border-blue-500"
                    value="{{ request('query') }}">
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg">🔍</button>
            </form>

            <!-- Hasil Pencarian -->
            @if (!empty($query))
                <div class="bg-white rounded-lg p-3 mb-4 shadow">
                    <h3 class="font-semibold">Hasil Pencarian</h3>
                    @forelse ($users as $user)
                        <a href="{{ route('messages.index', ['user' => $user->id]) }}"
                            class="block p-2 hover:bg-gray-200 rounded-lg flex items-center gap-3">
                            <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('images/default.jpg') }}"
                                class="w-10 h-10 rounded-full">
                            <span>{{ $user->name }}</span>
                        </a>
                    @empty
                        <p class="text-gray-500">User tidak ditemukan.</p>
                    @endforelse
                </div>
            @endif

            <!-- Daftar Akun yang Diikuti -->
            <h3 class="font-semibold mb-2">Akun yang Anda Ikuti</h3>
            <div class="flex gap-3 overflow-x-auto pb-3">
                @foreach ($followingUsers as $user)
                    <a href="{{ route('messages.index', ['user' => $user->id]) }}" class="text-center">
                        <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('images/default.jpg') }}"
                            class="w-12 h-12 rounded-full mx-auto">
                        <span class="text-sm">{{ $user->name }}</span>
                    </a>
                @endforeach
            </div>

            <!-- Daftar Percakapan -->
            <h3 class="font-semibold mt-4">Percakapan Anda</h3>
            @foreach ($conversations as $userId => $messages)
                @php
                    $user =
                        $messages->first()->sender_id == auth()->id()
                            ? $messages->first()->receiver
                            : $messages->first()->sender;
                    $unreadCount = $unreadCounts[$user->id] ?? 0;
                @endphp
                <a href="{{ route('messages.index', ['user' => $user->id]) }}"
                    class="relative flex items-center gap-4 p-3 bg-white rounded-lg shadow mb-2">
                    <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('images/default.jpg') }}"
                        class="w-10 h-10 rounded-full">
                    <h3 class="font-semibold">
                        {{ $user->name }} :
                        <span class="text-gray-600 text-sm">
                            {{ $messages->first()->message }}
                        </span>
                    </h3>
                    @if ($unreadCount > 0)
                        <span class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                            {{ $unreadCount }}
                        </span>
                    @endif
                </a>
            @endforeach
        </div>

        <!-- Chat Kanan -->
        <div class="w-2/3 bg-white p-4 flex flex-col">
            @if ($selectedUser)
                <h2 class="text-xl font-bold mb-4">Chat dengan {{ $selectedUser->name }}</h2>
                <div class="flex flex-col gap-2 overflow-y-auto h-full">
                    @foreach ($messages->sortBy('created_at') as $message)
                        <div
                            class="p-3 rounded-lg {{ $message->sender_id == auth()->id() ? 'bg-blue-500 text-white self-end' : 'bg-gray-200 self-start' }}">
                            {{ $message->message }}
                        </div>
                    @endforeach
                </div>

                <!-- Form Kirim Pesan -->
                <form action="{{ route('messages.store', ['user' => $selectedUser->id]) }}" method="POST"
                    class="flex gap-3">
                    @csrf
                    <input type="text" name="message" placeholder="Tulis pesan..." required
                        class="flex-1 p-2 border rounded-lg focus:outline-none focus:border-blue-500">
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg">Send</button>
                </form>
            @else
                <div class="flex justify-center items-center h-full">
                    <p class="text-gray-500">Pilih percakapan untuk memulai chat.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
