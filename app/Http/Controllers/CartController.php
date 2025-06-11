<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductSize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = Auth::user()->cart;

        if (!$cart) {
            $cart = Cart::create(['user_id' => Auth::id()]);
        }

        $cart->load('items.product.images', 'items.product.sizes');
        return view('cart', compact('cart'));
    }

    public function addItem(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'size' => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);

        $productSize = ProductSize::where('product_id', $request->product_id)
            ->where('size', $request->size)
            ->first();

        if (!$productSize || $productSize->quantity < $request->quantity) {
            return back()->with('error', 'Выбранное количество товара недоступно');
        }

        $cart = Auth::user()->cart;

        if (!$cart) {
            $cart = Cart::create(['user_id' => Auth::id()]);
        }

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $request->product_id)
            ->where('size', $request->size)
            ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $request->quantity;

            if ($productSize->quantity < $newQuantity) {
                return back()->with('error', 'Выбранное количество товара недоступно (в корзине уже есть ' . $cartItem->quantity . ' шт.)');
            }

            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $request->product_id,
                'size' => $request->size,
                'quantity' => $request->quantity,
            ]);
        }

        return redirect()->route('cart.index')
            ->with('success', 'Товар добавлен в корзину');
    }

    public function removeItem(Request $request, $id)
    {
        $request->validate([
            'size' => 'required|string',
        ]);

        $cart = Auth::user()->cart;

        if (!$cart) {
            return redirect()->route('cart.index');
        }

        CartItem::where('cart_id', $cart->id)
            ->where('product_id', $id)
            ->where('size', $request->size)
            ->delete();

        return back()->with('success', 'Товар удален из корзины');
    }

    public function clear()
    {
        $cart = Auth::user()->cart;

        if ($cart) {
            $cart->items()->delete();
        }

        return back()->with('success', 'Корзина очищена');
    }
}