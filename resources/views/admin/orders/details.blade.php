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
    
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    
    <!-- DELIVERY MANAGEMENT SECTION -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-5 mb-6">
        <h2 class="text-xl font-bold mb-4 flex items-center">
            <span class="text-2xl mr-2">📦</span> Delivery Management
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <form method="POST" action="{{ route('admin.orders.delivery-status', $order->id) }}" class="contents">
                @csrf
                @method('PUT')
                
                <!-- Current Delivery Status Display -->
                <div class="bg-white rounded-lg p-4">
                    <p class="text-sm text-gray-500 mb-1">Current Delivery Status</p>
                    <div class="text-lg font-semibold mb-3">
                        {!! $order->delivery_status_badge !!}
                    </div>
                    
                    <label class="block text-sm font-medium text-gray-700 mb-2">Update Delivery Status</label>
                    <select name="delivery_status" class="w-full border rounded px-3 py-2 mb-3">
                        <option value="pending" {{ $order->delivery_status == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                        <option value="processing" {{ $order->delivery_status == 'processing' ? 'selected' : '' }}>🔄 Processing</option>
                        <option value="shipped" {{ $order->delivery_status == 'shipped' ? 'selected' : '' }}>📦 Shipped</option>
                        <option value="delivered" {{ $order->delivery_status == 'delivered' ? 'selected' : '' }}>✅ Delivered</option>
                        <option value="cancelled" {{ $order->delivery_status == 'cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
                    </select>
                </div>
                
                <!-- Estimated Delivery Date Management -->
                <div class="bg-white rounded-lg p-4">
                    <p class="text-sm text-gray-500 mb-1">Estimated Delivery Date</p>
                    <p class="text-lg font-semibold text-green-600 mb-3">
                        @if($order->estimated_delivery_date)
                            {{ $order->estimated_delivery_date->format('F d, Y') }}
                            @if($order->estimated_delivery_date->isToday())
                                (Today!)
                            @elseif($order->estimated_delivery_date->isTomorrow())
                                (Tomorrow)
                            @endif
                        @else
                            Not set
                        @endif
                    </p>
                    
                    <label class="block text-sm font-medium text-gray-700 mb-2">Edit Estimated Delivery</label>
                    <input type="date" name="estimated_delivery_date" 
                           value="{{ $order->estimated_delivery_date ? $order->estimated_delivery_date->format('Y-m-d') : '' }}"
                           min="{{ date('Y-m-d') }}"
                           class="w-full border rounded px-3 py-2">
                </div>
                
                <div class="col-span-1 md:col-span-2 text-center mt-2">
                    <button type="submit" class="bg-blue-500 text-white px-8 py-3 rounded-lg hover:bg-blue-600 font-semibold text-lg">
                        💾 Save Delivery Information
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Delivery Progress Bar -->
        @if($order->estimated_delivery_date && $order->delivery_status != 'delivered')
        <div class="mt-4 pt-3">
            <div class="flex justify-between text-xs text-gray-600 mb-1">
                <span>📅 Ordered</span>
                <span>🔄 Processing</span>
                <span>📦 Shipped</span>
                <span>🏠 Delivered</span>
            </div>
            <div class="bg-gray-200 rounded-full h-3 overflow-hidden">
                @php
                    $progress = 0;
                    switch($order->delivery_status) {
                        case 'pending': $progress = 10; break;
                        case 'processing': $progress = 40; break;
                        case 'shipped': $progress = 75; break;
                        case 'delivered': $progress = 100; break;
                        default: $progress = 0;
                    }
                @endphp
                <div class="bg-blue-600 h-3 rounded-full transition-all duration-500" style="width: {{ $progress }}%"></div>
            </div>
            <div class="text-right text-xs text-gray-500 mt-1">
                {{ $progress }}% complete
            </div>
        </div>
        @elseif($order->delivery_status == 'delivered')
        <div class="mt-4 pt-3">
            <div class="bg-green-100 rounded-lg p-2 text-center">
                <p class="text-green-700 font-semibold">✅ Order Delivered Successfully on {{ $order->actual_delivery_date ? $order->actual_delivery_date->format('F d, Y') : 'N/A' }} ✅</p>
            </div>
        </div>
        @endif
    </div>
    
    <!-- Customer Information -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-gray-50 rounded-lg p-4">
            <h3 class="font-bold text-lg mb-2">👤 Customer Information</h3>
            <p><strong>Name:</strong> {{ $order->user->name }}</p>
            <p><strong>Email:</strong> {{ $order->user->email }}</p>
            <p><strong>Order Date:</strong> {{ $order->created_at->format('F d, Y h:i A') }}</p>
            <p><strong>Phone:</strong> {{ $order->user->phone ?? 'N/A' }}</p>
        </div>
        <div class="bg-gray-50 rounded-lg p-4">
            <h3 class="font-bold text-lg mb-2">📍 Shipping Information</h3>
            <p><strong>Address:</strong> {{ $order->shipping_address }}</p>
            <p><strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
            <p><strong>Payment Status:</strong> 
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
    
    <!-- Order Status Management -->
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
        <h3 class="font-bold text-lg mb-2">📋 Order Status Management</h3>
        <form method="POST" action="{{ route('admin.orders.status', $order->id) }}">
            @csrf
            @method('PUT')
            <div class="flex gap-2 items-center">
                <label class="font-semibold">Order Status:</label>
                <select name="status" class="border rounded px-3 py-2">
                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Update Order Status
                </button>
            </div>
        </form>
    </div>
    
    <!-- Order Items -->
    <h2 class="font-bold text-lg mb-4">Order Items</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead>
                <tr class="border-b bg-gray-50">
                    <th class="text-left py-2 px-2">Product</th>
                    <th class="text-left py-2 px-2">Quantity</th>
                    <th class="text-left py-2 px-2">Price</th>
                    <th class="text-left py-2 px-2">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr class="border-b">
                        <td class="py-2 px-2">{{ $item->product->name }}</td>
                        <td class="py-2 px-2">{{ $item->quantity }}</td>
                        <td class="py-2 px-2">₱{{ number_format($item->price, 2) }}</td>
                        <td class="py-2 px-2">₱{{ number_format($item->quantity * $item->price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="border-t bg-gray-50 font-bold">
                    <td colspan="3" class="text-right py-2 px-2">Total:</td>
                    <td class="py-2 px-2">₱{{ number_format($order->total_amount, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
    
    @if($order->bank_receipt)
    <div class="mt-6 pt-4 border-t">
        <h3 class="font-bold text-lg mb-2">📎 Payment Receipt</h3>
        <a href="{{ asset('storage/' . $order->bank_receipt) }}" target="_blank" class="text-blue-600 hover:underline">
            View Uploaded Receipt
        </a>
    </div>
    @endif
    
    @if($order->notes)
    <div class="mt-6 pt-4 border-t">
        <h3 class="font-bold text-lg mb-2">📝 Customer Notes</h3>
        <p class="text-gray-600 bg-gray-50 p-3 rounded">{{ $order->notes }}</p>
    </div>
    @endif
</div>
@endsection