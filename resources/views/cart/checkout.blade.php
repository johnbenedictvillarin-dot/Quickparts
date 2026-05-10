@extends('layouts.app')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h1 class="text-2xl font-bold mb-6">Checkout</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div>
            <h2 class="text-xl font-bold mb-4">Order Summary</h2>
            @foreach($cart->items as $item)
                <div class="flex justify-between mb-2">
                    <span>{{ $item->product->name }} x {{ $item->quantity }}</span>
                    <span>₱{{ number_format($item->quantity * $item->product->price, 2) }}</span>
                </div>
            @endforeach
            <div class="border-t pt-2 mt-2">
                <div class="flex justify-between font-bold text-lg">
                    <span>Total</span>
                    <span>₱{{ number_format($cart->items->sum(function($item) { 
                        return $item->quantity * $item->product->price; 
                    }), 2) }}</span>
                </div>
            </div>
        </div>
        
        <div>
            <h2 class="text-xl font-bold mb-4">Shipping Information</h2>
            <form method="POST" action="/orders">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Shipping Address</label>
                    <textarea name="shipping_address" required rows="3" 
                              class="w-full border rounded px-3 py-2"></textarea>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Payment Method</label>
                    <select name="payment_method" required class="w-full border rounded px-3 py-2">
                        <option value="cash_on_delivery">Cash on Delivery</option>
                        <option value="bank_transfer">Bank Transfer</option>
                    </select>
                </div>
                
                <button type="submit" class="w-full bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600 font-bold">
                    Place Order
                </button>
            </form>
        </div>
    </div>
</div>
@endsection