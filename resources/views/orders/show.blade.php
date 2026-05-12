@extends('layouts.app')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <!-- Order Header -->
    <div class="flex justify-between items-start mb-6 pb-4 border-b">
        <div>
            <h1 class="text-2xl font-bold">Order #{{ $order->order_number }}</h1>
            <p class="text-gray-600">Placed on {{ $order->created_at->format('F d, Y h:i A') }}</p>
        </div>
        <div>
            @if($order->payment_method == 'cod')
                <span class="px-3 py-1 rounded text-sm font-semibold bg-green-100 text-green-800">
                    💵 Cash on Delivery
                </span>
            @else
                <span class="px-3 py-1 rounded text-sm font-semibold bg-blue-100 text-blue-800">
                    🏦 Bank Transfer
                </span>
            @endif
        </div>
    </div>
    
    <!-- DELIVERY INFORMATION - Repositioned at the top -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-5 mb-6">
        <div class="flex items-center mb-3">
            <span class="text-2xl mr-2">📦</span>
            <h2 class="text-xl font-bold text-blue-800">Delivery Information</h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Estimated Delivery -->
            <div class="bg-white rounded-lg p-3 shadow-sm">
                <p class="text-xs text-gray-500 uppercase tracking-wide">Estimated Delivery</p>
                @if($order->estimated_delivery_date)
                    <p class="text-xl font-bold text-green-600">
                        {{ $order->estimated_delivery_date->format('F d, Y') }}
                    </p>
                    @if($order->estimated_delivery_date->isToday())
                        <p class="text-sm text-green-600 font-semibold">🎉 Today! 🎉</p>
                    @elseif($order->estimated_delivery_date->isTomorrow())
                        <p class="text-sm text-green-600 font-semibold">🚚 Tomorrow</p>
                    @else
                        <p class="text-sm text-gray-500">{{ $order->delivery_countdown }}</p>
                    @endif
                @else
                    <p class="text-gray-500">TBD</p>
                @endif
            </div>
            
            <!-- Delivery Status -->
            <div class="bg-white rounded-lg p-3 shadow-sm">
                <p class="text-xs text-gray-500 uppercase tracking-wide">Delivery Status</p>
                <div class="mt-1">
                    {!! $order->delivery_status_badge !!}
                </div>
                @if($order->delivery_status == 'shipped')
                    <p class="text-xs text-gray-500 mt-2">Your order is on the way!</p>
                @elseif($order->delivery_status == 'delivered')
                    <p class="text-xs text-green-600 mt-2">✓ Order has been delivered</p>
                @elseif($order->delivery_status == 'processing')
                    <p class="text-xs text-blue-600 mt-2">⚡ Being prepared for shipping</p>
                @endif
            </div>
            
            <!-- Order Date & Tracking -->
            <div class="bg-white rounded-lg p-3 shadow-sm">
                <p class="text-xs text-gray-500 uppercase tracking-wide">Order Date</p>
                <p class="font-semibold">{{ $order->created_at->format('F d, Y') }}</p>
                <p class="text-xs text-gray-500 mt-1">Order #: {{ $order->order_number }}</p>
            </div>
        </div>
        
        <!-- Delivery Progress Bar -->
        @if($order->estimated_delivery_date && !$order->estimated_delivery_date->isPast() && $order->delivery_status != 'delivered')
        <div class="mt-4 pt-3">
            <div class="flex justify-between text-xs text-gray-600 mb-1">
                <span>📅 Order Placed</span>
                <span>🚚 Processing</span>
                <span>📦 Shipped</span>
                <span>🏠 Delivered</span>
            </div>
            <div class="bg-gray-200 rounded-full h-2 overflow-hidden">
                @php
                    $totalDays = \Carbon\Carbon::parse($order->created_at)->diffInDays($order->estimated_delivery_date);
                    $daysPassed = \Carbon\Carbon::parse($order->created_at)->diffInDays(now());
                    $progress = min(($daysPassed / max($totalDays, 1)) * 100, 100);
                    
                    // Adjust progress based on delivery status
                    if ($order->delivery_status == 'shipped') $progress = 75;
                    if ($order->delivery_status == 'processing') $progress = 40;
                @endphp
                <div class="bg-blue-600 h-2 rounded-full transition-all duration-500" style="width: {{ $progress }}%"></div>
            </div>
            <div class="text-right text-xs text-gray-500 mt-1">
                {{ round($progress) }}% complete
            </div>
        </div>
        @elseif($order->delivery_status == 'delivered')
        <div class="mt-4 pt-3">
            <div class="bg-green-100 rounded-lg p-2 text-center">
                <p class="text-green-700 font-semibold">✅ Order Delivered Successfully! ✅</p>
            </div>
        </div>
        @endif
    </div>
    
    <!-- Shipping & Payment Information -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-gray-50 rounded-lg p-4">
            <h3 class="font-bold text-lg mb-2 flex items-center">
                <span class="mr-2">📍</span> Shipping Address
            </h3>
            <p class="text-gray-700">{{ $order->shipping_address }}</p>
        </div>
        <div class="bg-gray-50 rounded-lg p-4">
            <h3 class="font-bold text-lg mb-2 flex items-center">
                <span class="mr-2">💳</span> Payment Information
            </h3>
            <p class="text-gray-700">
                <strong>Method:</strong> {{ $order->payment_method == 'cod' ? 'Cash on Delivery' : 'Bank Transfer' }}<br>
                <strong>Status:</strong> 
                @if($order->payment_status == 'paid')
                    <span class="text-green-600">✓ Paid</span>
                @elseif($order->payment_status == 'awaiting_payment')
                    <span class="text-yellow-600">⏳ Awaiting Payment</span>
                @else
                    <span class="text-gray-600">Pending</span>
                @endif
            </p>
        </div>
    </div>
    
    <!-- Bank Transfer Instructions (if applicable) -->
    @if($order->payment_method == 'bank_transfer' && $order->payment_status == 'awaiting_payment')
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
            <h3 class="font-bold text-yellow-800 mb-2">📌 Payment Instructions</h3>
            <p class="text-sm text-yellow-700">Please complete your bank transfer to:</p>
            <div class="bg-white rounded p-3 mt-2">
                <p><strong>Bank:</strong> BDO Unibank</p>
                <p><strong>Account Name:</strong> QuickParts Trading</p>
                <p><strong>Account Number:</strong> 1234-5678-9012</p>
                <p><strong>Amount:</strong> ₱{{ number_format($order->total_amount, 2) }}</p>
                <p><strong>Reference:</strong> {{ $order->order_number }}</p>
            </div>
            <p class="text-xs text-yellow-600 mt-2">Once payment is confirmed, your order will be processed for delivery.</p>
        </div>
    @endif
    
    <!-- Order Items -->
    <h2 class="font-bold text-lg mb-4 flex items-center">
        <span class="mr-2">🛍️</span> Order Items
    </h2>
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead>
                <tr class="border-b bg-gray-50">
                    <th class="text-left py-3 px-2">Product</th>
                    <th class="text-left py-3 px-2">Quantity</th>
                    <th class="text-left py-3 px-2">Price</th>
                    <th class="text-left py-3 px-2">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-3 px-2">{{ $item->product->name }}</td>
                        <td class="py-3 px-2">x{{ $item->quantity }}</td>
                        <td class="py-3 px-2">₱{{ number_format($item->price, 2) }}</td>
                        <td class="py-3 px-2 font-semibold">₱{{ number_format($item->quantity * $item->price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="border-t bg-gray-50">
                    <td colspan="3" class="text-right py-3 px-2 font-bold">Total:</td>
                    <td class="py-3 px-2 text-xl font-bold text-blue-600">₱{{ number_format($order->total_amount, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
    
    @if($order->notes)
        <div class="mt-6 pt-4 border-t">
            <h3 class="font-bold text-lg mb-2">📝 Additional Notes</h3>
            <p class="text-gray-600 bg-gray-50 p-3 rounded">{{ $order->notes }}</p>
        </div>
    @endif
    
    <!-- Back Button -->
    <div class="mt-6 text-center">
        <a href="{{ route('orders.index') }}" class="inline-block bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600">
            ← Back to My Orders
        </a>
    </div>
</div>
@endsection