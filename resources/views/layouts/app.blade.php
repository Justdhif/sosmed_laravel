<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Home - Social Media')</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">

    <!-- Library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        ::-webkit-scrollbar {
            opacity: 0;
        }

        :hover::-webkit-scrollbar {
            opacity: 1;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
            }

            to {
                opacity: 0;
                transform: translateY(20px);
            }
        }

        .animate-slide-in {
            animation: slideIn 0.5s ease-out forwards;
        }

        .animate-fade-out {
            animation: fadeOut 0.5s ease-out forwards;
        }
    </style>

</head>

<body class="bg-gray-100 text-gray-900 font-sans">

    <div id="alert-container" class="fixed bottom-5 right-5 flex flex-col space-y-2 z-50">
        @if (session('success'))
            <div
                class="alert bg-green-100 border-l-4 border-green-500 text-green-700 shadow-lg rounded-lg p-4 w-80 flex items-center space-x-3 animate-slide-in">
                <div>✅</div>
                <div class="flex-1">
                    <p class="font-semibold">Success</p>
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
                <button onclick="closeAlert(this)" class="text-gray-400 hover:text-gray-600">✖</button>
            </div>
        @endif

        @if (session('error'))
            <div
                class="alert bg-red-100 border-l-4 border-red-500 text-red-700 shadow-lg rounded-lg p-4 w-80 flex items-start space-x-3 animate-slide-in">
                <div>⚠️</div>
                <div class="flex-1">
                    <p class="font-semibold">Error</p>
                    <p class="text-sm">{{ session('error') }}</p>
                </div>
                <button onclick="closeAlert(this)" class="text-gray-400 hover:text-gray-600">✖</button>
            </div>
        @endif
    </div>

    <div class="flex justify-between items-center h-screen">
        <!-- Sidebar -->
        @if (!Route::is('posts.show'))
            @include('layouts.sidebar')
        @endif

        <!-- Konten Utama -->
        <div
            class="w-full h-full relative flex-1 bg-white text-gray-900 p-6 overflow-x-hidden overflow-y-auto rounded-lg">

            @yield('content')

        </div>

    </div>

    <script>
        function closeAlert(element) {
            element.classList.add('animate-fade-out');
            setTimeout(() => element.remove(), 500);
        }

        document.addEventListener("DOMContentLoaded", function() {
            setTimeout(() => {
                document.querySelectorAll('.alert').forEach(alert => {
                    closeAlert(alert);
                });
            }, 4000);
        });
    </script>

</body>

</html>
