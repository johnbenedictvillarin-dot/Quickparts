@extends('layouts.app')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h1 class="text-2xl font-bold mb-6">Manage Orders</h1>
    
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead>
                <tr class="border-b">
                    <th class="text-left py-2">Order #</th>
                    <th class="text-left py-2">Customer</th>
                    <th class="text-left py-2">Total</th>
                    <th class="text-left py-2">Delivery Status</th>
                    <th class="text-left py-2">Est. Delivery</th>
                    <th class="text-left py-2">Order Status</th>
                    <th class="text-left py-2">Date</th>
                    <th class="text-left py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <tr class="border-b">
                        <td class="py-2">{{ $order->order_number }}</td>
                        <td class="py-2">{{ $order->user->name }}</td>
                        <td class="py-2">₱{{ number_format($order->total_amount, 2) }}</td>
                        <td class="py-2">
                            {!! $order->delivery_status_badge !!}
                        </tr>
                        <td class="py-2">
                            @if($order->estimated_delivery_date)
                                {{ $order->estimated_delivery_date->format('M d, Y') }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="py-2">
                            <span class="px-2 py-1 rounded text-xs 
                                @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                                @elseif($order->status == 'completed') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="py-2">{{ $order->created_at->format('M d, Y') }}</td>
                        <td class="py-2">
                            <a href="{{ route('admin.orders.details', $order->id) }}" class="text-blue-600 hover:underline">View Details</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $orders->links() }}
    </div>
</div>
@endsection