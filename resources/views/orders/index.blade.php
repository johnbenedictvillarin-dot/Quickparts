@extends('layouts.app')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h1 class="text-2xl font-bold mb-6">My Orders</h1>
    
    @if($orders->isEmpty())
        <p class="text-gray-600">You haven't placed any orders yet.</p>
        <a href="/products" class="inline-block mt-4 bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
            Start Shopping
        </a>
    @else
        <div class="space-y-4">
            @foreach($orders as $order)
                <div class="border rounded-lg p-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-bold">Order #{{ $order->order_number }}</p>
                            <p class="text-sm text-gray-600">{{ $order->created_at->format('M d, Y') }}</p>
                            <p class="text-sm mt-1">
                                Status: 
                                <span class="px-2 py-1 rounded text-xs 
                                    @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                                    @elseif($order->status == 'completed') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-xl font-bold">₱{{ number_format($order->total_amount, 2) }}</p>
                            <a href="/orders/{{ $order->id }}" class="text-blue-600 hover:underline text-sm">View Details</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection