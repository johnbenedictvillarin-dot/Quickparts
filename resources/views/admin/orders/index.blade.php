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
                    <th class="text-left py-2">Status</th>
                    <th class="text-left py-2">Date</th>
                    <th class="text-left py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr class="border-b">
                        <td class="py-2">{{ $order->order_number }}</td>
                        <td class="py-2">{{ $order->user->name ?? 'N/A' }}</td>
                        <td class="py-2">₱{{ number_format($order->total_amount, 2) }}</td>
                        <td class="py-2">
                            <form method="POST" action="{{ url('/admin/orders/' . $order->id . '/status') }}" class="inline-block">
                                @csrf
                                @method('PUT')
                                <select name="status" onchange="this.form.submit()" class="text-sm border rounded px-2 py-1">
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </form>
                        </td>
                        <td class="py-2">{{ $order->created_at->format('M d, Y') }}</td>
                        <td class="py-2">
                            <a href="{{ url('/admin/orders/' . $order->id . '/details') }}" class="text-blue-600 hover:underline">View Details</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-gray-500">No orders found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $orders->links() }}
    </div>
</div>
@endsection