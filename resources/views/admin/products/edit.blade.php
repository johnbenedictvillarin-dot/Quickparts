@extends('layouts.app')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h1 class="text-2xl font-bold mb-6">Edit Product</h1>
    
    <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Product Name *</label>
                    <input type="text" name="name" value="{{ $product->name }}" required class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500">
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Category *</label>
                    <select name="category_id" required class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Price *</label>
                    <input type="number" name="price" step="0.01" value="{{ $product->price }}" required class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500">
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Stock Quantity *</label>
                    <input type="number" name="stock" value="{{ $product->stock }}" required class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500">
                </div>
            </div>
            
            <div>
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Current Image</label>
                    @if($product->image)
                        <div class="mb-2">
                            <img src="{{ str_contains($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="max-h-48 mx-auto rounded">
                        </div>
                    @else
                        <div class="bg-gray-100 p-4 text-center rounded mb-2">No image uploaded</div>
                    @endif
                    
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center">
                        <div id="imagePreview" class="mb-2 hidden">
                            <img id="preview" src="#" alt="Preview" class="max-h-48 mx-auto">
                        </div>
                        <input type="file" name="image" accept="image/*" onchange="previewImage(event)" class="w-full">
                        <p class="text-sm text-gray-500 mt-2">Leave empty to keep current image. Max 2MB</p>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" {{ $product->is_active ? 'checked' : '' }} class="mr-2">
                        <span class="text-gray-700">Active (visible to customers)</span>
                    </label>
                </div>
            </div>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Description *</label>
            <textarea name="description" required rows="5" class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500">{{ $product->description }}</textarea>
        </div>
        
        <div class="flex justify-end">
            <a href="{{ route('admin.products') }}" class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600 mr-2">
                Cancel
            </a>
            <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                Update Product
            </button>
        </div>
    </form>
</div>

<script>
function previewImage(event) {
    const reader = new FileReader();
    const preview = document.getElementById('preview');
    const imagePreview = document.getElementById('imagePreview');
    
    reader.onload = function() {
        if (reader.result) {
            preview.src = reader.result;
            imagePreview.classList.remove('hidden');
        }
    }
    
    if (event.target.files[0]) {
        reader.readAsDataURL(event.target.files[0]);
    }
}
</script>
@endsection