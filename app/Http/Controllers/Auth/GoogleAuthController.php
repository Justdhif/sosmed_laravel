<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    // Redirect ke Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Handle callback dari Google
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Ambil nama dari email
            $username = explode('@', $googleUser->email)[0];

            // Cek apakah user sudah ada berdasarkan email
            $user = User::updateOrCreate(
                ['email' => $googleUser->email],
                [
                    'name' => $username, // Gunakan username dari email
                    'email' => $googleUser->email,
                    'password' => bcrypt(str()->random(16)), // Set password random (tidak digunakan)
                    'google_id' => $googleUser->id,
                ]
            );

            Auth::login($user);

            return redirect('/home')->with('success', 'Berhasil login menggunakan Google');
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
