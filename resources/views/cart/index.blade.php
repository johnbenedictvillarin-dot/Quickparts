@extends('layouts.app')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h1 class="text-2xl font-bold mb-6">Shopping Cart</h1>
    
    @if($cart->items->isEmpty())
        <p class="text-gray-600">Your cart is empty</p>
        <a href="/products" class="inline-block mt-4 bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
            Continue Shopping
        </a>
    @else
        <div class="space-y-4">
            @foreach($cart->items as $item)
                <div class="flex items-center justify-between border-b pb-4">
                    <div class="flex-1">
                        <h3 class="font-bold">{{ $item->product->name }}</h3>
                        <p class="text-gray-600">₱{{ number_format($item->product->price, 2) }} each</p>
                    </div>
                    
                    <form method="POST" action="/cart/update/{{ $item->id }}" class="flex items-center gap-2">
                        @csrf
                        @method('PUT')
                        <input type="number" name="quantity" value="{{ $item->quantity }}" 
                               min="1" max="{{ $item->product->stock }}" class="border rounded px-2 py-1 w-20">
                        <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded text-sm">Update</button>
                    </form>
                    
                    <div class="w-32 text-right">
                        <span class="font-bold">₱{{ number_format($item->quantity * $item->product->price, 2) }}</span>
                    </div>
                    
                    <form method="POST" action="/cart/remove/{{ $item->id }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800">Remove</button>
                    </form>
                </div>
            @endforeach
            
            <div class="flex justify-between items-center mt-6 pt-4 border-t">
                <div class="text-xl font-bold">
                    Total: ₱{{ number_format($cart->items->sum(function($item) { 
                        return $item->quantity * $item->product->price; 
                    }), 2) }}
                </div>
                <a href="/checkout" class="bg-green-500 text-white px-6 py-2 rounded hover:bg-green-600">
                    Proceed to Checkout
                </a>
            </div>
        </div>
    @endif
</div>
@endsection