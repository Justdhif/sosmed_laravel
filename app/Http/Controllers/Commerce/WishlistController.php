<?php

namespace App\Http\Controllers\Commerce;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    // Menampilkan daftar Wishlist
    public function index()
    {
        $wishlist = Wishlist::where('user_id', Auth::id())->with('product')->get();
        return view('wishlist.index', compact('wishlist'));
    }

    // Menambahkan produk ke Wishlist
    public function add($productId)
    {
        if (!$this->isProductInWishlist($productId)) {
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $productId
            ]);
            return redirect()->back()->with('success', 'Produk ditambahkan ke Wishlist');
        }

        return redirect()->back()->with('info', 'Produk sudah ada di Wishlist');
    }

    // Menghapus produk dari Wishlist
    public function remove($id)
    {
        $this->removeFromWishlist($id);
        return redirect()->back()->with('success', 'Produk dihapus dari Wishlist');
    }

    // Memeriksa apakah produk ada di Wishlist
    protected function isProductInWishlist($productId)
    {
        return Wishlist::where('user_id', Auth::id())->where('product_id', $productId)->exists();
    }

    // Menghapus item dari Wishlist
    protected function removeFromWishlist($id)
    {
        Wishlist::where('user_id', Auth::id())->where('id', $id)->delete();
    }
}
