@extends('layouts.app')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h1 class="text-2xl font-bold mb-6">Admin Dashboard</h1>
    
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-blue-100 p-4 rounded-lg">
            <p class="text-gray-600">Total Orders</p>
            <p class="text-2xl font-bold">{{ $totalOrders }}</p>
        </div>
        <div class="bg-green-100 p-4 rounded-lg">
            <p class="text-gray-600">Total Products</p>
            <p class="text-2xl font-bold">{{ $totalProducts }}</p>
        </div>
        <div class="bg-yellow-100 p-4 rounded-lg">
            <p class="text-gray-600">Total Customers</p>
            <p class="text-2xl font-bold">{{ $totalUsers }}</p>
        </div>
        <div class="bg-red-100 p-4 rounded-lg">
            <p class="text-gray-600">Pending Orders</p>
            <p class="text-2xl font-bold">{{ $pendingOrders }}</p>
        </div>
    </div>

    <!-- Order Status Summary -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-yellow-50 p-3 rounded border border-yellow-200">
            <p class="text-sm text-yellow-600">Pending</p>
            <p class="text-xl font-bold">{{ $ordersByStatus['pending'] }}</p>
        </div>
        <div class="bg-blue-50 p-3 rounded border border-blue-200">
            <p class="text-sm text-blue-600">Processing</p>
            <p class="text-xl font-bold">{{ $ordersByStatus['processing'] }}</p>
        </div>
        <div class="bg-green-50 p-3 rounded border border-green-200">
            <p class="text-sm text-green-600">Completed</p>
            <p class="text-xl font-bold">{{ $ordersByStatus['completed'] }}</p>
        </div>
        <div class="bg-red-50 p-3 rounded border border-red-200">
            <p class="text-sm text-red-600">Cancelled</p>
            <p class="text-xl font-bold">{{ $ordersByStatus['cancelled'] }}</p>
        </div>
    </div>

    <!-- Recent Orders Table -->
    <h2 class="text-xl font-bold mb-4">Recent Orders</h2>
    <div class="overflow-x-auto mb-8">
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
                @foreach($recentOrders as $order)
                    <tr class="border-b">
                        <td class="py-2">{{ $order->order_number }}</td>
                        <td class="py-2">{{ $order->user->name }}</td>
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
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Recent Customers -->
    <h2 class="text-xl font-bold mb-4">Recent Customers</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead>
                <tr class="border-b">
                    <th class="text-left py-2">Name</th>
                    <th class="text-left py-2">Email</th>
                    <th class="text-left py-2">Joined</th>
                    <th class="text-left py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentCustomers as $customer)
                    <tr class="border-b">
                        <td class="py-2">{{ $customer->name }}</td>
                        <td class="py-2">{{ $customer->email }}</td>
                        <td class="py-2">{{ $customer->created_at->format('M d, Y') }}</td>
                        <td class="py-2">
                            <a href="{{ url('/admin/customers/' . $customer->id . '/orders') }}" class="text-blue-600 hover:underline">View Orders</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4 text-center">
        <a href="{{ url('/admin/customers') }}" class="text-blue-600 hover:underline">View All Customers →</a>
    </div>
</div>
@endsection