<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit($id)
    {
        $user = Auth::user()->findOrFail($id);
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user()->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg|max:4096',
            'bio' => 'nullable|string|max:500',
        ]);

        // Simpan foto profil jika ada unggahan
        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture && $user->profile_picture !== 'profile_pictures/default.jpg') {
                Storage::delete('public/' . $user->profile_picture);
            }
            $profilePath = $request->file('profile_picture')->store('profile_pictures', 'public');
        } else {
            $profilePath = $user->profile_picture ?? 'profile_pictures/default.jpg';
        }

        // Simpan background jika ada unggahan
        if ($request->hasFile('background_image')) {
            if ($user->background_image && $user->background_image !== 'profile_backgrounds/default-bg.jpg') {
                Storage::delete('public/' . $user->background_image);
            }
            $backgroundPath = $request->file('background_image')->store('profile_backgrounds', 'public');
        } else {
            $backgroundPath = $user->background_image ?? 'profile_backgrounds/default-bg.jpg';
        }

        $user->update([
            'name' => $request->name,
            'bio' => $request->bio,
            'profile_picture' => $profilePath,
            'background_image' => $backgroundPath,
        ]);

        return redirect()->route('profile.show', $user->name)->with('success', 'Profil berhasil diperbarui.');
    }
}

