<?php

namespace App\Http\Controllers\Commerce;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Product;
use Midtrans\Config;
use Midtrans\Snap;

class CheckoutController extends Controller
{
    public function show()
    {
        $cartItems = Cart::where('user_id', auth()->id())->get();
        $totalPrice = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return view('checkout.index', compact('cartItems', 'totalPrice'));
    }

    public function process(Request $request)
    {
        $cartItems = Cart::where('user_id', auth()->id())->get();
        if ($cartItems->isEmpty()) {
            return redirect()->route('checkout.show')->with('error', 'Keranjang belanja kosong.');
        }

        $totalPrice = $cartItems->sum(fn ($item) => $item->product->price * $item->quantity);

        // Simpan Order ke database
        $order = Order::create([
            'user_id' => auth()->id(),
            'total_price' => $totalPrice,
            'status' => 'pending',
        ]);

        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        // Buat payload untuk Midtrans
        $transactionDetails = [
            'transaction_details' => [
                'order_id' => $order->id,
                'gross_amount' => $totalPrice,
            ],
            'customer_details' => [
                'first_name' => auth()->user()->name,
                'email' => auth()->user()->email,
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($transactionDetails);
            $order->update(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            return redirect()->route('checkout.show')->with('error', 'Gagal memproses pembayaran.');
        }

        return redirect()->route('checkout.pay', ['token' => $snapToken]);
    }

    public function pay($token)
    {
        return view('checkout.pay', compact('token'));
    }

    public function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed != $request->signature_key) {
            return abort(403, 'Unauthorized');
        }

        $order = Order::find($request->order_id);
        if (!$order) {
            return abort(404, 'Order tidak ditemukan');
        }

        if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
            $order->update(['status' => 'paid']);

            // Kurangi stok produk setelah pembayaran berhasil
            foreach ($order->cart as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->decrement('stock', $item->quantity);
                }
            }

            return redirect()->route('checkout.success');
        } elseif ($request->transaction_status == 'pending') {
            return redirect()->route('checkout.pending');
        } else {
            return redirect()->route('checkout.failed');
        }
    }
}
