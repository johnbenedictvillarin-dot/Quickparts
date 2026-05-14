@extends('layouts.app')

@section('content')
<div class="flex flex-col lg:flex-row gap-6">
    <!-- Sidebar -->
    <div class="lg:w-72 flex-shrink-0">
        <!-- Categories -->
        <div class="bg-white rounded-lg shadow-sm mb-4">
            <div class="p-4 border-b">
                <h3 class="font-bold text-lg">Categories</h3>
            </div>
            <div class="p-3">
                <a href="/products" class="flex items-center justify-between py-2 px-3 hover:bg-blue-50 rounded transition-all duration-300">
                    <span class="text-gray-700">All Products</span>
                    <span class="text-gray-400">›</span>
                </a>
                @foreach($categories as $category)
                    <a href="?category={{ $category->id }}" class="flex items-center justify-between py-2 px-3 hover:bg-blue-50 rounded transition-all duration-300">
                        <span class="text-gray-700">{{ $category->name }}</span>
                        <span class="text-gray-400">›</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="flex-1">
        <!-- Search and Sort Bar -->
        <div class="bg-white rounded-lg shadow-sm p-4 mb-4 flex flex-wrap gap-3 justify-between items-center">
            <div class="flex-1 max-w-md">
                <form method="GET" class="flex">
                    <input type="text" name="search" placeholder="Search products..." value="{{ request('search') }}"
                           class="flex-1 border rounded-l-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-r-lg hover:bg-blue-600 transition">Search</button>
                </form>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-gray-600 text-sm">Sort by:</span>
                <select id="sortBy" onchange="sortProducts()" class="border rounded-lg px-3 py-2 text-sm">
                    <option value="newest">Newest</option>
                    <option value="price_low">Price: Low to High</option>
                    <option value="price_high">Price: High to Low</option>
                    <option value="rating">Top Rated</option>
                </select>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" id="productsGrid">
            @foreach($products as $index => $product)
                <div class="product-card bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-lg transition-all duration-300 group cursor-pointer" onclick="window.location='/products/{{ $product->slug }}'" data-index="{{ $index }}">
                    <!-- Product Image with Badge -->
                    <div class="relative bg-gray-100">
                        <div class="absolute top-2 left-2 z-10">
                            @if($product->stock <= 5 && $product->stock > 0)
                                <span class="bg-red-500 text-white text-xs px-2 py-1 rounded">Low Stock</span>
                            @endif
                            @if($product->stock <= 0)
                                <span class="bg-gray-500 text-white text-xs px-2 py-1 rounded">Out of Stock</span>
                            @endif
                        </div>
                        <div class="absolute top-2 right-2 z-10">
                            <button class="wishlist-btn bg-white rounded-full p-1.5 shadow hover:bg-red-50 transition" onclick="event.stopPropagation(); toggleWishlist(this)">
                                🤍
                            </button>
                        </div>
                        <div class="h-48 flex items-center justify-center p-4">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" 
                                     class="max-h-full max-w-full object-contain transition-transform duration-300 group-hover:scale-105">
                            @else
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-400">No image</div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Product Info -->
                    <div class="p-3">
                        <h3 class="font-medium text-gray-800 line-clamp-2 h-12 mb-2 text-sm">{{ $product->name }}</h3>
                        
                        <!-- Price -->
                        <div class="mb-2">
                            <span class="text-xl font-bold text-red-500">₱{{ number_format($product->price, 2) }}</span>
                            @if($product->price > 5000)
                                <span class="text-xs text-gray-400 line-through ml-2">₱{{ number_format($product->price * 1.2, 2) }}</span>
                            @endif
                        </div>
                        
                        <!-- Accurate Rating Stars from Database -->
                        <div class="flex items-center gap-1 mb-2">
                            <div class="flex text-yellow-400 text-sm">
                                @php
                                    $rating = $product->rating ?? 0;
                                    $fullStars = floor($rating);
                                    $halfStar = ($rating - $fullStars) >= 0.5;
                                    $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                                @endphp
                                @for($i = 1; $i <= $fullStars; $i++)
                                    <span>★</span>
                                @endfor
                                @if($halfStar)
                                    <span>½</span>
                                @endif
                                @for($i = 1; $i <= $emptyStars; $i++)
                                    <span class="text-gray-300">★</span>
                                @endfor
                            </div>
                            <span class="text-xs text-gray-500">{{ number_format($rating, 1) }} ({{ number_format($product->review_count ?? 0) }})</span>
                        </div>
                        
                        <!-- Location / Seller -->
                        <div class="flex items-center gap-1 text-xs text-gray-500">
                            <span>📍</span>
                            <span>QuickParts Official</span>
                        </div>
                        
                        <!-- Free Shipping Badge -->
                        @if($product->price >= 1000)
                            <div class="mt-2">
                                <span class="text-green-600 text-xs font-medium">🚚 Free Shipping</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $products->links() }}
        </div>
    </div>
</div>

<style>
    @keyframes slideInLeft {
        from { opacity: 0; transform: translateX(-50px); }
        to { opacity: 1; transform: translateX(0); }
    }
    
    @keyframes slideInRight {
        from { opacity: 0; transform: translateX(50px); }
        to { opacity: 1; transform: translateX(0); }
    }
    
    @keyframes slideInDown {
        from { opacity: 0; transform: translateY(-30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .product-card {
        transition: all 0.3s ease;
        cursor: pointer;
        opacity: 0;
        animation: fadeInUp 0.5s ease-out forwards;
    }
    
    .product-card:hover {
        transform: translateY(-4px);
    }
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .wishlist-btn.active {
        color: red;
    }
    
    @keyframes heartBeat {
        0% { transform: scale(1); }
        50% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }
    
    .wishlist-btn:active {
        animation: heartBeat 0.3s ease;
    }
    
    /* Staggered animation delays */
    .product-card[data-index="0"] { animation-delay: 0s; }
    .product-card[data-index="1"] { animation-delay: 0.05s; }
    .product-card[data-index="2"] { animation-delay: 0.1s; }
    .product-card[data-index="3"] { animation-delay: 0.15s; }
    .product-card[data-index="4"] { animation-delay: 0.2s; }
    .product-card[data-index="5"] { animation-delay: 0.25s; }
    .product-card[data-index="6"] { animation-delay: 0.3s; }
    .product-card[data-index="7"] { animation-delay: 0.35s; }
    .product-card[data-index="8"] { animation-delay: 0.4s; }
    .product-card[data-index="9"] { animation-delay: 0.45s; }
    .product-card[data-index="10"] { animation-delay: 0.5s; }
</style>

<script>
    function sortProducts() {
        const sortBy = document.getElementById('sortBy').value;
        let url = new URL(window.location.href);
        url.searchParams.set('sort', sortBy);
        window.location.href = url;
    }
    
    function toggleWishlist(btn) {
        btn.classList.toggle('active');
        if (btn.classList.contains('active')) {
            btn.innerHTML = '❤️';
            let productCard = btn.closest('.product-card');
            let productName = productCard.querySelector('h3').innerText;
            console.log('Added to wishlist: ' + productName);
        } else {
            btn.innerHTML = '🤍';
        }
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.product-card');
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px)';
            });
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    });
</script>
@endsection