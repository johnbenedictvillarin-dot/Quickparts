@extends('layouts.app')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h1 class="text-2xl font-bold mb-6">All Customers</h1>
    
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead>
                <tr class="border-b">
                    <th class="text-left py-2">Name</th>
                    <th class="text-left py-2">Email</th>
                    <th class="text-left py-2">Total Orders</th>
                    <th class="text-left py-2">Joined Date</th>
                    <th class="text-left py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customers as $customer)
                    <tr class="border-b">
                        <td class="py-2">{{ $customer->name }}</td>
                        <td class="py-2">{{ $customer->email }}</td>
                        <td class="py-2">{{ $customer->orders_count }}</td>
                        <td class="py-2">{{ $customer->created_at->format('M d, Y') }}</td>
                        <td class="py-2">
                            <a href="{{ route('admin.customers.orders', $customer->id) }}" class="text-blue-600 hover:underline">View Orders</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $customers->links() }}
    </div>
</div>
@endsection