@extends('layouts.app')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div>
            <div class="h-96 bg-gray-200 rounded-lg flex items-center justify-center">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="h-full w-full object-cover rounded-lg">
                @else
                    <span class="text-gray-400">Product Image</span>
                @endif
            </div>
        </div>
        
        <div>
            <h1 class="text-3xl font-bold">{{ $product->name }}</h1>
            <p class="text-gray-600 mt-2">{{ $product->category->name }}</p>
            <p class="text-3xl font-bold text-blue-600 mt-4">₱{{ number_format($product->price, 2) }}</p>
            <p class="text-gray-600 mt-2">Stock: {{ $product->stock }} units</p>
            
            <div class="mt-4">
                <h3 class="font-bold text-lg">Description</h3>
                <p class="text-gray-700 mt-2">{{ $product->description }}</p>
            </div>
            
            @auth
                @if($product->stock > 0)
                    <form method="POST" action="/cart/add/{{ $product->id }}" class="mt-6">
                        @csrf
                        <div class="flex gap-4">
                            <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" 
                                   class="border rounded px-3 py-2 w-24">
                            <button type="submit" class="flex-1 bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">
                                Add to Cart
                            </button>
                        </div>
                    </form>
                @else
                    <div class="mt-6 bg-red-100 text-red-700 p-3 rounded">
                        Out of stock
                    </div>
                @endif
            @else
                <div class="mt-6 bg-yellow-100 text-yellow-700 p-3 rounded">
                    <a href="/login" class="underline">Login</a> to purchase this product
                </div>
            @endauth
        </div>
    </div>
    
    @if($relatedProducts->count() > 0)
        <div class="mt-12">
            <h2 class="text-2xl font-bold mb-4">Related Products</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @foreach($relatedProducts as $related)
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="font-bold">{{ $related->name }}</h3>
                        <p class="text-blue-600 font-bold mt-2">₱{{ number_format($related->price, 2) }}</p>
                        <a href="/products/{{ $related->slug }}" class="block text-center bg-blue-500 text-white px-3 py-1 rounded mt-2 text-sm">
                            View
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection