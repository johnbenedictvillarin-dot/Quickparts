@extends('layouts.app')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h1 class="text-2xl font-bold mb-6">Sales Report</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-blue-100 p-4 rounded-lg">
            <p class="text-gray-600">Total Sales</p>
            <p class="text-2xl font-bold">₱{{ number_format($totalSales ?? 0, 2) }}</p>
        </div>
        <div class="bg-green-100 p-4 rounded-lg">
            <p class="text-gray-600">Completed Orders</p>
            <p class="text-2xl font-bold">{{ $totalOrders ?? 0 }}</p>
        </div>
    </div>
    
    <h2 class="text-xl font-bold mb-4">Sales Information</h2>
    <div class="bg-gray-50 p-4 rounded-lg">
        <p class="text-gray-600">Total revenue from all completed orders.</p>
        <p class="text-gray-600 mt-2">Average order value: ₱{{ number_format(($totalSales ?? 0) / max(($totalOrders ?? 0), 1), 2) }}</p>
    </div>
</div>
@endsection