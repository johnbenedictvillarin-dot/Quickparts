<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string',
            'payment_method' => 'required|string'
        ]);

        $cart = Auth::user()->cart;
        if (!$cart) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }
        
        $cart->load('items.product');

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        foreach ($cart->items as $item) {
            if ($item->quantity > $item->product->stock) {
                return back()->with('error', "Insufficient stock for {$item->product->name}");
            }
        }

        $order = Order::create([
            'order_number' => 'ORD-' . Str::random(8),
            'user_id' => Auth::id(),
            'total_amount' => $cart->items->sum(function($item) {
                return $item->quantity * $item->product->price;
            }),
            'status' => 'pending',
            'shipping_address' => $request->shipping_address,
            'payment_method' => $request->payment_method
        ]);

        foreach ($cart->items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price
            ]);

            $item->product->decrement('stock', $item->quantity);
        }

        $cart->items()->delete();

        return redirect()->route('orders.show', $order)->with('success', 'Order placed successfully');
    }

    public function index()
    {
        $orders = Auth::user()->orders()->with('items.product')->orderBy('created_at', 'desc')->paginate(10);
        
        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }
        
        $order->load('items.product');
        
        return view('orders.show', compact('order'));
    }
}