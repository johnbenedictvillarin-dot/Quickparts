@extends('layouts.app')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-2xl font-bold">Order Details</h1>
            <p class="text-gray-600">Order #: {{ $order->order_number }}</p>
        </div>
        <a href="{{ route('admin.orders') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
            Back to Orders
        </a>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
        <div>
            <h2 class="font-bold text-lg mb-2">Customer Information</h2>
            <p><strong>Name:</strong> {{ $order->user->name }}</p>
            <p><strong>Email:</strong> {{ $order->user->email }}</p>
            <p><strong>Order Date:</strong> {{ $order->created_at->format('F d, Y h:i A') }}</p>
        </div>
        <div>
            <h2 class="font-bold text-lg mb-2">Shipping Information</h2>
            <p>{{ $order->shipping_address }}</p>
            <p><strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
        </div>
    </div>
    
    <div class="mb-8">
        <h2 class="font-bold text-lg mb-2">Order Status</h2>
        <form method="POST" action="{{ route('admin.orders.status', $order) }}">
            @csrf
            @method('PUT')
            <select name="status" class="border rounded px-3 py-2">
                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 ml-2">
                Update Status
            </button>
        </form>
    </div>
    
    <h2 class="font-bold text-lg mb-4">Order Items</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead>
                <tr class="border-b">
                    <th class="text-left py-2">Product</th>
                    <th class="text-left py-2">Quantity</th>
                    <th class="text-left py-2">Price</th>
                    <th class="text-left py-2">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr class="border-b">
                        <td class="py-2">{{ $item->product->name }}</td>
                        <td class="py-2">{{ $item->quantity }}</td>
                        <td class="py-2">₱{{ number_format($item->price, 2) }}</td>
                        <td class="py-2">₱{{ number_format($item->quantity * $item->price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="border-t font-bold">
                    <td colspan="3" class="text-right py-2">Total:</td>
                    <td class="py-2">₱{{ number_format($order->total_amount, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection