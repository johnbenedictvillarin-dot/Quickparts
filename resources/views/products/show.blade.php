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
            
            <!-- Rating Display -->
            <div class="flex items-center gap-2 mt-2">
                <div class="flex text-yellow-400 text-lg">
                    @php
                        $fullStars = floor($product->rating ?? 0);
                        $halfStar = ($product->rating - $fullStars) >= 0.5;
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
                <span class="text-sm text-gray-600">{{ number_format($product->rating ?? 0, 1) }} out of 5</span>
                <span class="text-sm text-gray-500">({{ number_format($product->review_count ?? 0) }} ratings)</span>
            </div>
            
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
    
    <!-- Rating and Review Section -->
    <div class="mt-8 border-t pt-6">
        <h2 class="font-bold text-xl mb-4">Rate this Product</h2>
        
        @auth
            @php
                $userRating = Auth::user()->ratings()->where('product_id', $product->id)->first();
            @endphp
            
            <form method="POST" action="{{ route('product.rating', $product) }}" class="mb-8">
                @csrf
                
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2 font-medium">Your Rating</label>
                    <div class="star-rating flex gap-2 text-4xl cursor-pointer" id="starRating">
                        <span data-value="1" class="star">☆</span>
                        <span data-value="2" class="star">☆</span>
                        <span data-value="3" class="star">☆</span>
                        <span data-value="4" class="star">☆</span>
                        <span data-value="5" class="star">☆</span>
                    </div>
                    <input type="hidden" name="rating" id="ratingValue" value="{{ $userRating->rating ?? 0 }}">
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2 font-medium">Your Review (Optional)</label>
                    <textarea name="review" rows="3" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Share your experience with this product...">{{ $userRating->review ?? '' }}</textarea>
                </div>
                
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition">
                    {{ $userRating ? 'Update Rating' : 'Submit Rating' }}
                </button>
            </form>
            
            <style>
                .star {
                    transition: all 0.2s ease;
                    cursor: pointer;
                    color: #d1d5db;
                }
                .star.active {
                    color: #fbbf24;
                }
                .star:hover {
                    transform: scale(1.1);
                }
            </style>
            
            <script>
                // Star rating functionality
                const stars = document.querySelectorAll('.star');
                const ratingInput = document.getElementById('ratingValue');
                let currentRating = parseInt(ratingInput.value) || 0;
                
                function highlightStars(rating) {
                    stars.forEach((star, index) => {
                        if (index < rating) {
                            star.innerHTML = '★';
                            star.classList.add('active');
                        } else {
                            star.innerHTML = '☆';
                            star.classList.remove('active');
                        }
                    });
                }
                
                stars.forEach(star => {
                    star.addEventListener('click', function() {
                        currentRating = parseInt(this.dataset.value);
                        ratingInput.value = currentRating;
                        highlightStars(currentRating);
                    });
                    
                    star.addEventListener('mouseenter', function() {
                        const value = parseInt(this.dataset.value);
                        highlightStars(value);
                    });
                    
                    star.addEventListener('mouseleave', function() {
                        highlightStars(currentRating);
                    });
                });
                
                // Initialize stars
                highlightStars(currentRating);
            </script>
        @else
            <p class="text-gray-600 mb-6">
                <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login</a> to rate this product.
            </p>
        @endauth
        
        <!-- Display Existing Reviews -->
        <div class="mt-8">
            <h3 class="font-bold text-lg mb-4">Customer Reviews ({{ $product->ratings->count() }})</h3>
            
            @if($product->ratings->count() > 0)
                @foreach($product->ratings as $rating)
                    <div class="border-b pb-4 mb-4">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="flex text-yellow-400">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $rating->rating)
                                        <span>★</span>
                                    @else
                                        <span class="text-gray-300">★</span>
                                    @endif
                                @endfor
                            </div>
                            <span class="text-sm text-gray-500">{{ $rating->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-gray-700">{{ $rating->review ?: 'No review written.' }}</p>
                        <p class="text-xs text-gray-400 mt-1">By {{ $rating->user->name }}</p>
                    </div>
                @endforeach
            @else
                <p class="text-gray-500">No reviews yet. Be the first to rate this product!</p>
            @endif
        </div>
    </div>
    
    @if($relatedProducts->count() > 0)
        <div class="mt-12">
            <h2 class="text-2xl font-bold mb-4">Related Products</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @foreach($relatedProducts as $related)
                    <div class="bg-gray-50 rounded-lg p-4 hover:shadow-lg transition">
                        <div class="h-32 flex items-center justify-center mb-2">
                            @if($related->image)
                                <img src="{{ asset('storage/' . $related->image) }}" alt="{{ $related->name }}" class="h-full object-contain">
                            @else
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center text-xs">No image</div>
                            @endif
                        </div>
                        <h3 class="font-bold text-sm line-clamp-2">{{ $related->name }}</h3>
                        <p class="text-blue-600 font-bold mt-2">₱{{ number_format($related->price, 2) }}</p>
                        
                        <!-- Related product rating -->
                        <div class="flex items-center gap-1 mt-1">
                            <div class="flex text-yellow-400 text-xs">
                                @php
                                    $relFullStars = floor($related->rating ?? 0);
                                @endphp
                                @for($i = 1; $i <= $relFullStars; $i++)
                                    <span>★</span>
                                @endfor
                                @for($i = $relFullStars + 1; $i <= 5; $i++)
                                    <span class="text-gray-300">★</span>
                                @endfor
                            </div>
                            <span class="text-xs text-gray-500">({{ number_format($related->review_count ?? 0) }})</span>
                        </div>
                        
                        <a href="/products/{{ $related->slug }}" class="block text-center bg-blue-500 text-white px-3 py-1 rounded mt-2 text-sm hover:bg-blue-600 transition">
                            View Details
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection