<?php

namespace App\Http\Controllers\Commerce;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller {
    public function index() {
        $wishlist = Wishlist::where('user_id', Auth::id())->with('product')->get();
        return view('wishlist.index', compact('wishlist'));
    }

    public function add($productId) {
        if (!Wishlist::where('user_id', Auth::id())->where('product_id', $productId)->exists()) {
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $productId
            ]);
        }
        return redirect()->back()->with('success', 'Produk ditambahkan ke Wishlist');
    }

    public function remove($id) {
        Wishlist::where('user_id', Auth::id())->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Produk dihapus dari Wishlist');
    }
}
