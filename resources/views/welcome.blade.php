@extends('layouts.app')

@section('content')
<div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-lg p-12 mb-8 text-center">
    <h1 class="text-4xl font-bold mb-4">Welcome to QuickParts</h1>
    <p class="text-xl mb-6">Your One-Stop Shop for Quality Motorcycle Parts</p>
    <a href="/products" class="bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100">
        Shop Now
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6 text-center">
        <div class="text-3xl mb-3">🏍️</div>
        <h3 class="font-bold text-lg mb-2">Quality Parts</h3>
        <p class="text-gray-600">Premium motorcycle parts from trusted brands</p>
    </div>
    <div class="bg-white rounded-lg shadow p-6 text-center">
        <div class="text-3xl mb-3">🚚</div>
        <h3 class="font-bold text-lg mb-2">Fast Shipping</h3>
        <p class="text-gray-600">Quick delivery to your doorstep</p>
    </div>
    <div class="bg-white rounded-lg shadow p-6 text-center">
        <div class="text-3xl mb-3">💯</div>
        <h3 class="font-bold text-lg mb-2">Best Prices</h3>
        <p class="text-gray-600">Competitive prices on all products</p>
    </div>
</div>
@endsection