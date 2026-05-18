@extends('layouts.app')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Manage Products</h1>
        <a href="{{ route('admin.products.create') }}" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
            + Add New Product
        </a>
    </div>
    
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead>
                <tr class="border-b">
                    <th class="text-left py-2">Image</th>
                    <th class="text-left py-2">Name</th>
                    <th class="text-left py-2">Category</th>
                    <th class="text-left py-2">Price</th>
                    <th class="text-left py-2">Stock</th>
                    <th class="text-left py-2">Status</th>
                    <th class="text-left py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                    <tr class="border-b">
                        <td class="py-2">
    @if($product->image)
        <img src="{{ str_contains($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-12 h-12 object-cover rounded">
    @else
        <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center text-gray-400 text-xs">No img</div>
    @endif
</td>
                        <td class="py-2">
                            <a href="/products/{{ $product->slug }}" class="text-blue-600 hover:underline" target="_blank">
                                {{ $product->name }}
                            </a>
                        </td>
                        <td class="py-2">{{ $product->category->name }}</td>
                        <td class="py-2">₱{{ number_format($product->price, 2) }}</td>
                        <td class="py-2">
                            @if($product->stock <= 5)
                                <span class="text-red-600 font-bold">{{ $product->stock }}</span>
                            @else
                                {{ $product->stock }}
                            @endif
                        </td>
                        <td class="py-2">
                            @if($product->is_active)
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Active</span>
                            @else
                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Inactive</span>
                            @endif
                        </td>
                        <td class="py-2">
                            <a href="/products/{{ $product->slug }}" class="text-green-600 hover:underline mr-2" target="_blank">View</a>
                            <a href="{{ route('admin.products.edit', $product) }}" class="text-blue-600 hover:underline mr-2">Edit</a>
                            <form method="POST" action="{{ route('admin.products.delete', $product) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this product?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $products->links() }}
    </div>
</div>
@endsection