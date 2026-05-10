@extends('layouts.app')

@section('content')
<div class="flex">
    <!-- Sidebar with categories -->
    <div class="w-64 mr-8">
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="font-bold text-lg mb-4">Categories</h3>
            <a href="/products" class="block text-gray-700 hover:text-blue-600 mb-2">All Products</a>
            @foreach($categories as $category)
                <a href="?category={{ $category->id }}" class="block text-gray-700 hover:text-blue-600 mb-2">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
    </div>

    <!-- Products grid -->
    <div class="flex-1">
        <!-- Search bar -->
        <form method="GET" class="mb-6">
            <div class="flex gap-2">
                <input type="text" name="search" placeholder="Search products..." 
                       class="flex-1 border rounded-lg px-4 py-2" value="{{ request('search') }}">
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">
                    Search
                </button>
            </div>
        </form>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($products as $product)
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="h-48 bg-gray-200 flex items-center justify-center">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                        @else
                            <span class="text-gray-400">No image</span>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-lg">{{ $product->name }}</h3>
                        <p class="text-gray-600 text-sm mt-1">{{ Str::limit($product->description, 100) }}</p>
                        <div class="mt-2">
                            <span class="text-2xl font-bold text-blue-600">₱{{ number_format($product->price, 2) }}</span>
                            <span class="text-sm text-gray-500">Stock: {{ $product->stock }}</span>
                        </div>
                        <div class="mt-4">
                            <a href="/products/{{ $product->slug }}" class="block text-center bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection