@extends('layouts.app')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold">Customer Orders</h1>
            <p class="text-gray-600">Customer: <strong>{{ $customer->name }}</strong> ({{ $customer->email }})</p>
        </div>
        <a href="{{ route('admin.customers.all') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
            Back to Customers
        </a>
    </div>
    
    @if($orders->isEmpty())
        <p class="text-gray-600">This customer hasn't placed any orders yet.</p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b">
                        <th class="text-left py-2">Order #</th>
                        <th class="text-left py-2">Total Amount</th>
                        <th class="text-left py-2">Status</th>
                        <th class="text-left py-2">Date</th>
                        <th class="text-left py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr class="border-b">
                            <td class="py-2">{{ $order->order_number }}</td>
                            <td class="py-2">₱{{ number_format($order->total_amount, 2) }}</td>
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
    @endif
</div>
@endsection