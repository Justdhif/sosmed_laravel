<?php

namespace App\Http\Controllers\Commerce;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    // Menampilkan daftar semua produk
    public function index()
    {
        $products = Product::all();
        $cartCount = Cart::where('user_id', Auth::id())->count();
        $wishlistCount = Wishlist::where('user_id', Auth::id())->count();
        return view('shop.products.index', compact('products', 'cartCount', 'wishlistCount'));
    }

    // Menampilkan form untuk menambah produk
    public function create()
    {
        if (!$this->hasShopProfile()) {
            return redirect()->route('shop.create')->with('error', 'Anda harus memiliki profil toko untuk menambahkan produk.');
        }
        return view('shop.products.create');
    }

    public function store(Request $request)
    {
        if (!$this->hasShopProfile()) {
            return redirect()->route('shop.create')->with('error', 'Anda harus memiliki profil toko untuk menambahkan produk.');
        }

        $request->validate($this->validationRules());

        $imagePath = $request->file('image') ? $request->file('image')->store('products', 'public') : null;

        Product::create([
            'shop_profile_id' => Auth::user()->shopProfile->id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'image_path' => $imagePath,
        ]);

        return redirect()->route('profile.show', ['name' => Auth::user()->name])->with('success', 'Produk berhasil ditambahkan.');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
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
        if (Auth::id() !== $product->shopProfile->user_id) {
            return redirect()->route('products.index')->with('error', 'Anda tidak memiliki izin untuk mengedit produk ini.');
        }

        $request->validate($this->validationRules(false));

        $product->fill($request->only(['name', 'description', 'price']));

        if ($request->hasFile('image')) {
            $product->image_path = $request->file('image')->store('products', 'public');
        }

        $product->save();

        return redirect()->route('products.show', $product->id)->with('success', 'Produk berhasil diperbarui.');
    }

    private function hasShopProfile()
    {
        return Auth::user()->shopProfile !== null;
    }

    private function validationRules($isStore = true)
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => $isStore ? 'required|integer|min:0' : 'nullable|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }
}
