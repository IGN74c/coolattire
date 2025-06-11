<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductSize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Auth::user()->orders()->with('items.product')->latest()->get();
        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load('items.product');
        return view('orders.show', compact('order'));
    }

    public function store(Request $request)
    {
        $cart = Auth::user()->cart;

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Ваша корзина пуста');
        }

        $cart->load('items.product.images');

        foreach ($cart->items as $item) {
            $productSize = ProductSize::where('product_id', $item->product_id)
                ->where('size', $item->size)
                ->first();

            if (!$productSize) {
                return redirect()->route('cart.index')
                    ->with('error', 'Товар "' . $item->product->name . '" размера ' . $item->size . ' больше не доступен');
            }

            if ($productSize->quantity < $item->quantity) {
                return redirect()->route('cart.index')
                    ->with('error', 'Товар "' . $item->product->name . '" размера ' . $item->size . ' доступен только в количестве ' . $productSize->quantity . ' шт.');
            }
        }

        $order = Order::create([
            'user_id' => Auth::id(),
            'status' => 'новый',
        ]);

        foreach ($cart->items as $item) {
            $order->items()->create([
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
                'size' => $item->size,
            ]);

            $productSize = ProductSize::where('product_id', $item->product_id)
                ->where('size', $item->size)
                ->first();

            $productSize->decrement('quantity', $item->quantity);
        }

        $cart->items()->delete();

        return redirect()->route('orders.show', $order)
            ->with('success', 'Заказ успешно оформлен');
    }

    public function destroy(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->status !== 'новый') {
            return back()->with('error', 'Можно отменить только новые заказы');
        }

        $order->load('items');

        foreach ($order->items as $item) {
            $productSize = ProductSize::where('product_id', $item->product_id)
                ->where('size', $item->size)
                ->first();

            if ($productSize) {
                $productSize->increment('quantity', $item->quantity);
            }
        }

        $order->update(['status' => 'отменен']);

        return redirect()->route('orders.index')
            ->with('success', 'Заказ успешно отменен, товары возвращены на склад');
    }
}