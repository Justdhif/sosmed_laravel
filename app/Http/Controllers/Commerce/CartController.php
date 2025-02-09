<?php

namespace App\Http\Controllers\Commerce;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller {
    public function index() {
        $cart = Cart::where('user_id', Auth::id())->with('product')->get();
        $totalPrice = $cart->sum(fn($item) => $item->product->price * $item->quantity);
        return view('cart.index', compact('cart', 'totalPrice'));
    }

    public function add($productId) {
        $cartItem = Cart::where('user_id', Auth::id())->where('product_id', $productId)->first();

        if ($cartItem) {
            $cartItem->increment('quantity');
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $productId,
                'quantity' => 1
            ]);
        }
        return redirect()->back()->with('success', 'Produk ditambahkan ke Keranjang');
    }

    public function remove($id) {
        Cart::where('user_id', Auth::id())->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Produk dihapus dari Keranjang');
    }

    public function updateQuantity(Request $request, $id) {
        Cart::where('user_id', Auth::id())->where('id', $id)->update(['quantity' => $request->quantity]);
        return redirect()->back()->with('success', 'Jumlah produk diperbarui');
    }
}
