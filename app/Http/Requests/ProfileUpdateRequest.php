<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg|max:4096',
            'bio' => 'nullable|string|max:500',
        ];
    }

    public function authorize()
    {
        return true; // Sesuaikan dengan logika otorisasi Anda
    }
}
