<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        // Validate based on payment method
        $rules = [
            'shipping_address' => 'required|string',
            'payment_method' => 'required|in:cod,bank_transfer',
        ];
        
        // Add notes validation only if provided
        if ($request->has('notes')) {
            $rules['notes'] = 'nullable|string';
        }
        
        // If bank transfer, require receipt
        if ($request->payment_method == 'bank_transfer') {
            $rules['bank_receipt'] = 'required|file|mimes:jpg,jpeg,png,pdf|max:2048';
        }
        
        $request->validate($rules);

        $cart = Auth::user()->cart;
        if (!$cart) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }
        
        $cart->load('items.product');

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        // Check stock availability
        foreach ($cart->items as $item) {
            if ($item->quantity > $item->product->stock) {
                return back()->with('error', "Insufficient stock for {$item->product->name}");
            }
        }

        // Handle receipt upload (only for bank transfer)
        $receiptPath = null;
        if ($request->payment_method == 'bank_transfer' && $request->hasFile('bank_receipt')) {
            $receiptPath = $request->file('bank_receipt')->store('receipts', 'public');
        }

        // Calculate total amount
        $totalAmount = $cart->items->sum(function($item) {
            return $item->quantity * $item->product->price;
        });

        // Create order number
        $orderNumber = 'ORD-' . strtoupper(Str::random(8));

        // Prepare order data
        $orderData = [
            'order_number' => $orderNumber,
            'user_id' => Auth::id(),
            'total_amount' => $totalAmount,
            'shipping_address' => $request->shipping_address,
        ];

        // Add payment method specific fields
        if ($request->payment_method == 'cod') {
            $orderData['status'] = 'pending';
            $orderData['payment_method'] = 'cod';
        } else {
            $orderData['status'] = 'pending_payment';
            $orderData['payment_method'] = 'bank_transfer';
            $orderData['payment_status'] = 'awaiting_payment';
            $orderData['bank_receipt'] = $receiptPath;
        }

        // Add notes if provided
        if ($request->filled('notes')) {
            $orderData['notes'] = $request->notes;
        }

        // Create order
        $order = Order::create($orderData);

        // Create order items and update stock
        foreach ($cart->items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price
            ]);

            // Update product stock
            $item->product->decrement('stock', $item->quantity);
        }

        // Clear cart
        $cart->items()->delete();

        // Success message based on payment method
        if ($request->payment_method == 'cod') {
            return redirect()->route('orders.show', $order)->with('success', 'Order placed successfully! Pay when you receive your items.');
        } else {
            return redirect()->route('orders.show', $order)->with('success', 'Order placed successfully! Your receipt has been uploaded. We will verify your payment soon.');
        }
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