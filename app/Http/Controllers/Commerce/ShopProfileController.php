<?php

namespace App\Http\Controllers\Commerce;

use App\Http\Controllers\Controller;
use App\Models\ShopProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class ShopProfileController extends Controller
{
    // 1. Menampilkan Form Pembuatan Toko
    public function create()
    {
        $followingUsers = auth()->user()->following;
        return view('shop.create', compact('followingUsers'));
    }

    // 2. Simpan data ke session dan redirect ke halaman OTP
    public function store(Request $request)
    {
        $request->validate($this->validationRules());

        // Simpan gambar jika diunggah
        $imagePath = $this->storeImage($request->file('image'));

        // Simpan data ke session
        session([
            'shop_name' => $request->name,
            'shop_description' => $request->description,
            'shop_image_path' => $imagePath,
            'otp' => rand(100000, 999999) // Simulasi OTP
        ]);

        return redirect()->route('shop.profile.otp');
    }

    // 3. Menampilkan halaman OTP
    public function otp()
    {
        if (!session()->has('otp')) {
            return redirect()->route('shop.profile.create')->with('error', 'Silakan isi data toko terlebih dahulu.');
        }

        $followingUsers = auth()->user()->following;

        return view('shop.otp', [
            'otp' => session('otp'),
            'followingUsers' => $followingUsers
        ]);
    }

    // 4. Verifikasi OTP dan simpan ke database
    public function verify(Request $request)
    {
        $this->validateOtp($request);

        // Pastikan data toko tersedia di session
        if (!session()->has('shop_name') || !session()->has('shop_description')) {
            return back()->with('error', 'Data toko tidak lengkap.');
        }

        // Simpan profil toko ke database
        ShopProfile::create([
            'user_id' => Auth::id(),
            'name' => session('shop_name'),
            'description' => session('shop_description'),
            'image_path' => session('shop_image_path'),
        ]);

        // Hapus session setelah sukses
        session()->forget(['otp', 'shop_name', 'shop_description', 'shop_image_path']);

        return redirect()->route('profile.show', ['name' => Auth::user()->name])->with('success', 'Profil toko berhasil dibuat!');
    }

    // 5. Menampilkan daftar toko
    public function index()
    {
        $shopProfile = ShopProfile::where('user_id', Auth::id())->first();
        $followingUsers = auth()->user()->following;

        return view('shop.index', compact('shopProfile', 'followingUsers'));
    }

    public function edit(ShopProfile $shop)
    {
        $this->authorizeShopEdit($shop);

        return view('shop.edit', compact('shop'));
    }

    public function update(Request $request, ShopProfile $shop)
    {
        $this->authorizeShopEdit($shop);

        $request->validate($this->updateValidationRules());

        $shop->update($request->only(['name', 'description']));

        return redirect()->route('profile.show', ['name' => Auth::user()->name])->with('success', 'Profil toko berhasil diperbarui.');
    }

    // Validasi untuk pembuatan toko
    protected function validationRules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ];
    }

    // Validasi untuk update toko
    protected function updateValidationRules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ];
    }

    // Simpan gambar dan kembalikan path
    protected function storeImage($image)
    {
        return $image ? $image->store('shop_images', 'public') : null;
    }

    // Validasi OTP
    protected function validateOtp(Request $request)
    {
        $otpArray = $request->input('otp');

        if (!is_array($otpArray) || count($otpArray) !== 6) {
            return back()->with('error', 'Kode OTP tidak valid.');
        }

        $otp = implode('', $otpArray);

        if ($otp != session('otp')) {
            return back()->with('error', 'Kode OTP salah.');
        }
    }

    // Otorisasi untuk mengedit toko
    protected function authorizeShopEdit(ShopProfile $shop)
    {
        if (Auth::id() !== $shop->user_id) {
            return redirect()->route('profile.show', ['name' => Auth::user()->name])->with('error', 'Anda tidak memiliki izin untuk mengedit toko ini.');
        }
    }
}
