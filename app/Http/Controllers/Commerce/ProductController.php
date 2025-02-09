<?php

namespace App\Http\Controllers\Commerce;

use App\Http\Controllers\Controller;

use App\Models\Product;
use App\Models\ShopProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Menampilkan daftar semua produk
    public function index()
    {
        $products = Product::all();
        return view('shop.products.index', compact('products'));
    }

    // Menampilkan daftar produk yang dimiliki oleh profil toko
    public function create()
    {
        $shopProfile = Auth::user()->shopProfile;
        if (!$shopProfile) {
            return redirect()->route('shop.create')->with('error', 'Anda harus memiliki profil toko untuk menambahkan produk.');
        }

        return view('shop.products.create');
    }

    public function store(Request $request)
    {
        $shopProfile = Auth::user()->shopProfile;
        if (!$shopProfile) {
            return redirect()->route('shop.create')->with('error', 'Anda harus memiliki profil toko untuk menambahkan produk.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imagePath = $request->file('image') ? $request->file('image')->store('products', 'public') : null;

        Product::create([
            'shop_profile_id' => $shopProfile->id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'image_path' => $imagePath,
        ]);

        return redirect()->route('profile.show', ['name' => $shopProfile->user->name])->with('success', 'Produk berhasil ditambahkan.');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        // Ambil produk yang cocok dengan nama atau deskripsi
        $products = Product::where('name', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->get();

        return view('shop.products.index', compact('products', 'query'));
    }

    public function show($id)
    {
        $product = Product::with('ratings.user')->findOrFail($id);
        return view('shop.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        if (Auth::id() !== $product->shopProfile->user_id) {
            return redirect()->route('products.index')->with('error', 'Anda tidak memiliki izin untuk mengedit produk ini.');
        }

        return view('shop.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        if (Auth::id() !== $product->shop->user_id) {
            return redirect()->route('products.index')->with('error', 'Anda tidak memiliki izin untuk mengedit produk ini.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'image' => 'nullable|image|max:2048',
        ]);

        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $product->image_path = $path;
        }

        $product->save();

        return redirect()->route('products.show', $product->id)->with('success', 'Produk berhasil diperbarui.');
    }
}
