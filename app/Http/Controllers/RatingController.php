<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000'
        ]);

        $rating = Rating::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'product_id' => $product->id
            ],
            [
                'rating' => $request->rating,
                'review' => $request->review
            ]
        );

        // Update product average rating
        $avgRating = Rating::where('product_id', $product->id)->avg('rating');
        $reviewCount = Rating::where('product_id', $product->id)->count();
        
        $product->update([
            'rating' => round($avgRating, 1),
            'review_count' => $reviewCount
        ]);

        return redirect()->back()->with('success', 'Thank you for your rating!');
    }
}