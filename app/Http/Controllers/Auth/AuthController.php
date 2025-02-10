<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            return redirect()->intended(route('home')); // Menggunakan route
        }

        return back()->withErrors([
            'password' => 'The provided password is incorrect.',
        ])->withInput();
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $data['profile_picture'] = $this->storeImage($request->file('profile_picture'), 'profile_pictures', 'default.jpg');
        $data['background_image'] = $this->storeImage($request->file('background_image'), 'profile_backgrounds', 'default-bg.jpg');
        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->route('login')->with('success', 'Registration successful. Please login.'); // Menggunakan route
    }

    protected function storeImage($file, $folder, $default)
    {
        return $file ? $file->store($folder, 'public') : "$folder/$default";
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have successfully logged out.'); // Menggunakan route
    }
}

