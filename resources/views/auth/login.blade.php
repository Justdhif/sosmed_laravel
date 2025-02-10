<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <!-- Container for Login Form -->
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-3xl font-bold text-center text-indigo-600 mb-6">Log in</h2>
        <form action="{{ route('login.submit') }}" method="POST" class="space-y-6 mb-4">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                <input type="email" name="email" id="email" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="example@domain.com" required>
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" id="password" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="••••••••" required>
            </div>

            <!-- Remember Me -->
            <div class="flex items-center">
                <input type="checkbox" name="remember" id="remember" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                <label for="remember" class="ml-2 text-sm text-gray-700">Remember me</label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 transition duration-200">
                Log in
            </button>
        </form>

        <p class="mb-4 text-center font-bold">
            atau
        </p>

        <div class="w-full text-black py-2 rounded-lg text-center shadow-md shadow-slate-200">
            <a href="{{ route('google.login') }}" class="font-bold flex justify-center items-center gap-3">
                <img src="{{ asset('images/google.png') }}" alt="" class="w-5 h-5">
                Login dengan Google
            </a>
        </div>

        <p class="text-center text-gray-500 text-sm mt-6">
            Forgot your password? <a href="" class="text-indigo-600 hover:underline">Reset here</a>.
        </p>

        <p class="text-center text-gray-500 text-sm mt-2">
            Don't have an account? <a href="{{ route('register') }}" class="text-indigo-600 hover:underline">Sign up</a>.
        </p>
    </div>

</body>
</html>
