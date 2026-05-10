@extends('layouts.app')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-2xl font-bold">Order #{{ $order->order_number }}</h1>
            <p class="text-gray-600">Placed on {{ $order->created_at->format('F d, Y') }}</p>
        </div>
        <div>
            <span class="px-3 py-1 rounded text-sm font-semibold
                @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                @elseif($order->status == 'completed') bg-green-100 text-green-800
                @else bg-red-100 text-red-800
                @endif">
                {{ ucfirst($order->status) }}
            </span>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
        <div>
            <h2 class="font-bold text-lg mb-2">Shipping Address</h2>
            <p class="text-gray-700">{{ $order->shipping_address }}</p>
        </div>
        <div>
            <h2 class="font-bold text-lg mb-2">Payment Method</h2>
            <p class="text-gray-700">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
        </div>
    </div>
    
    <h2 class="font-bold text-lg mb-4">Order Items</h2>
    <div class="space-y-2">
        @foreach($order->items as $item)
            <div class="flex justify-between items-center border-b pb-2">
                <div>
                    <p class="font-semibold">{{ $item->product->name }}</p>
                    <p class="text-sm text-gray-600">Quantity: {{ $item->quantity }}</p>
                </div>
                <p class="font-bold">₱{{ number_format($item->price * $item->quantity, 2) }}</p>
            </div>
        @endforeach
    </div>
    
    <div class="border-t pt-4 mt-4">
        <div class="flex justify-end">
            <div class="w-64">
                <div class="flex justify-between mb-2">
                    <span>Subtotal:</span>
                    <span>₱{{ number_format($order->total_amount, 2) }}</span>
                </div>
                <div class="flex justify-between font-bold text-lg">
                    <span>Total:</span>
                    <span>₱{{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection