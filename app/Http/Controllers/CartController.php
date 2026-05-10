<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
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
        $cart->load('items.product');
        
        return view('cart.index', compact('cart'));
    }

    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->stock
        ]);

        $cart = Auth::user()->cart;
        if (!$cart) {
            $cart = Cart::create(['user_id' => Auth::id()]);
        }
        
        $cartItem = CartItem::where('cart_id', $cart->id)
                           ->where('product_id', $product->id)
                           ->first();
        
        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $request->quantity;
            if ($newQuantity <= $product->stock) {
                $cartItem->update(['quantity' => $newQuantity]);
            } else {
                return back()->with('error', 'Cannot add more than available stock');
            }
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity
            ]);
        }
        
        return redirect()->route('cart.index')->with('success', 'Product added to cart');
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $cartItem->product->stock
        ]);
        
        $cartItem->update(['quantity' => $request->quantity]);
        
        return redirect()->route('cart.index')->with('success', 'Cart updated');
    }

    public function remove(CartItem $cartItem)
    {
        $cartItem->delete();
        
        return redirect()->route('cart.index')->with('success', 'Item removed from cart');
    }

    public function checkout()
    {
        $cart = Auth::user()->cart;
        if (!$cart) {
            $cart = Cart::create(['user_id' => Auth::id()]);
        }
        $cart->load('items.product');
        
        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }
        
        return view('cart.checkout', compact('cart'));
    }
}