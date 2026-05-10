@extends('layouts.app')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h1 class="text-2xl font-bold mb-6">Add New Product</h1>
    
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Product Name *</label>
                    <input type="text" name="name" required value="{{ old('name') }}" 
                           class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500">
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Category *</label>
                    <select name="category_id" required class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Price *</label>
                    <input type="number" name="price" step="0.01" required value="{{ old('price') }}" 
                           class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500">
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Stock Quantity *</label>
                    <input type="number" name="stock" required value="{{ old('stock') }}" 
                           class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500">
                </div>
            </div>
            
            <div>
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Product Image</label>
                    <input type="file" name="image" accept="image/*" 
                           class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500">
                    <p class="text-sm text-gray-500 mt-1">Supported formats: JPG, PNG, GIF (Max 2MB)</p>
                </div>
                
                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" checked class="mr-2">
                        <span class="text-gray-700">Active (visible to customers)</span>
                    </label>
                </div>
            </div>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Description *</label>
            <textarea name="description" required rows="5" 
                      class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500">{{ old('description') }}</textarea>
        </div>
        
        <div class="flex justify-end">
            <a href="{{ route('admin.products') }}" class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600 mr-2">
                Cancel
            </a>
            <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                Create Product
            </button>
        </div>
    </form>
</div>
@endsection