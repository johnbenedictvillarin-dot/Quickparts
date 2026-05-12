@extends('layouts.app')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h1 class="text-2xl font-bold mb-6">Checkout</h1>
    
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Order Summary -->
        <div>
            <h2 class="text-xl font-bold mb-4">Order Summary</h2>
            <div class="border rounded-lg p-4">
                @foreach($cart->items as $item)
                    <div class="flex justify-between mb-2 pb-2 border-b">
                        <div>
                            <span class="font-semibold">{{ $item->product->name }}</span>
                            <span class="text-gray-600 text-sm ml-2">x{{ $item->quantity }}</span>
                        </div>
                        <span>₱{{ number_format($item->quantity * $item->product->price, 2) }}</span>
                    </div>
                @endforeach
                
                <div class="flex justify-between mt-4 pt-2 border-t font-bold text-lg">
                    <span>Total</span>
                    <span>₱{{ number_format($cart->items->sum(function($item) { 
                        return $item->quantity * $item->product->price; 
                    }), 2) }}</span>
                </div>
            </div>
        </div>
        
        <!-- Payment & Shipping Form -->
        <div>
            <form method="POST" action="{{ route('orders.store') }}" enctype="multipart/form-data" id="orderForm">
                @csrf
                
                <div class="mb-4">
                    <h2 class="text-xl font-bold mb-4">Shipping Information</h2>
                    <label class="block text-gray-700 mb-2">Shipping Address *</label>
                    <textarea name="shipping_address" required rows="3" 
                              class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500"
                              placeholder="Enter your complete shipping address">{{ old('shipping_address') }}</textarea>
                </div>
                
                <div class="mb-4">
                    <h2 class="text-xl font-bold mb-4">Payment Method</h2>
                    
                    <!-- COD Option -->
                    <div class="border rounded-lg p-4 mb-3 cursor-pointer hover:bg-gray-50" 
                         onclick="selectPayment('cod')">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="payment_method" value="cod" required 
                                   class="mr-3 w-5 h-5" onclick="showPaymentDetails('cod')">
                            <div class="flex-1">
                                <span class="font-bold text-lg">💵 Cash on Delivery (COD)</span>
                                <p class="text-gray-600 text-sm">Pay when you receive the item</p>
                            </div>
                        </label>
                    </div>
                    
                    <!-- Bank Transfer Option -->
                    <div class="border rounded-lg p-4 mb-3 cursor-pointer hover:bg-gray-50" 
                         onclick="selectPayment('bank')">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="payment_method" value="bank_transfer" required 
                                   class="mr-3 w-5 h-5" onclick="showPaymentDetails('bank')">
                            <div class="flex-1">
                                <span class="font-bold text-lg">🏦 Bank Transfer</span>
                                <p class="text-gray-600 text-sm">Pay via bank transfer</p>
                            </div>
                        </label>
                    </div>
                </div>
                
                <!-- Bank Transfer Details (Hidden by default) -->
                <div id="bankDetails" class="hidden bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                    <h3 class="font-bold text-blue-800 mb-2">🏦 Bank Transfer Instructions</h3>
                    <p class="text-sm text-blue-700 mb-2">Please transfer the total amount to:</p>
                    <div class="bg-white rounded p-3 mb-2">
                        <p><strong>Bank:</strong> BDO Unibank</p>
                        <p><strong>Account Name:</strong> QuickParts Trading</p>
                        <p><strong>Account Number:</strong> 1234-5678-9012</p>
                    </div>
                    <p class="text-xs text-blue-600">After payment, upload your receipt below. Order will not proceed without receipt.</p>
                </div>
                
                <!-- Receipt Upload (Required for Bank Transfer) -->
                <div id="receiptUpload" class="hidden mb-4">
                    <label class="block text-gray-700 mb-2 font-semibold">Upload Payment Receipt *</label>
                    <input type="file" name="bank_receipt" accept="image/*,.pdf" 
                           class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500">
                    <p class="text-xs text-red-500 mt-1">* Required for bank transfer</p>
                    <p class="text-xs text-gray-500">Accepted formats: JPG, PNG, PDF (Max 2MB)</p>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Additional Notes (Optional)</label>
                    <textarea name="notes" rows="2" 
                              class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500"
                              placeholder="Any special instructions?"></textarea>
                </div>
                
                <button type="submit" class="w-full bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600 font-bold text-lg">
                    Place Order
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function selectPayment(method) {
        const bankDetails = document.getElementById('bankDetails');
        const receiptUpload = document.getElementById('receiptUpload');
        const receiptInput = document.querySelector('input[name="bank_receipt"]');
        
        if (method === 'bank') {
            bankDetails.classList.remove('hidden');
            receiptUpload.classList.remove('hidden');
            if (receiptInput) receiptInput.required = true;
        } else {
            bankDetails.classList.add('hidden');
            receiptUpload.classList.add('hidden');
            if (receiptInput) receiptInput.required = false;
        }
    }
    
    function showPaymentDetails(method) {
        selectPayment(method);
    }
    
    // Validate form before submit
    document.getElementById('orderForm').addEventListener('submit', function(e) {
        const selectedPayment = document.querySelector('input[name="payment_method"]:checked');
        if (!selectedPayment) {
            e.preventDefault();
            alert('Please select a payment method');
            return false;
        }
        
        if (selectedPayment.value === 'bank_transfer') {
            const receipt = document.querySelector('input[name="bank_receipt"]');
            if (receipt && !receipt.files.length) {
                e.preventDefault();
                alert('Please upload your payment receipt for bank transfer');
                return false;
            }
        }
    });
</script>
@endsection