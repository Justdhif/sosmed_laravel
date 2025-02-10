<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit($id)
    {
        $user = Auth::user()->findOrFail($id);
        return view('profile.edit', compact('user'));
    }

    public function update(ProfileUpdateRequest $request, $id)
    {
        $user = Auth::user()->findOrFail($id);

        $data = $this->handleImageUploads($request, $user);

        $user->update(array_merge($request->only('name', 'bio'), $data));

        return redirect()->route('profile.show', $user->name)->with('success', 'Profil berhasil diperbarui.'); // Menggunakan route
    }

    protected function handleImageUploads(ProfileUpdateRequest $request, $user)
    {
        return [
            'profile_picture' => $this->storeImage($request->file('profile_picture'), $user->profile_picture, 'profile_pictures/default.jpg'),
            'background_image' => $this->storeImage($request->file('background_image'), $user->background_image, 'profile_backgrounds/default-bg.jpg'),
        ];
    }

    protected function storeImage($file, $currentPath, $defaultPath)
    {
        if ($file) {
            if ($currentPath && $currentPath !== $defaultPath) {
                Storage::delete('public/' . $currentPath);
            }
            return $file->store(pathinfo($defaultPath, PATHINFO_DIRNAME), 'public');
        }
        return $currentPath ?? $defaultPath;
    }
}
