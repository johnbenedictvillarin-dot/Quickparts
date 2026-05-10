@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto bg-white rounded-lg shadow p-6">
    <h1 class="text-2xl font-bold mb-6 text-center">Verify Your Email</h1>
    
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {!! session('success') !!}
        </div>
    @endif
    
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif
    
    @if(session('warning'))
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
            {{ session('warning') }}
        </div>
    @endif
    
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
    
    <!-- Send Code Form -->
    <form method="POST" action="{{ route('send.otp') }}" class="mb-6">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Email Address</label>
            <input type="email" name="email" value="{{ old('email') ?? session('email') }}" required 
                   class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500"
                   placeholder="your@email.com">
        </div>
        <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Send Verification Code
        </button>
    </form>
    
    <!-- Verify Code Form -->
    <form method="POST" action="{{ route('verify.otp') }}" class="border-t pt-6">
        @csrf
        <h2 class="text-lg font-bold mb-4">Enter Verification Code</h2>
        
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Email Address</label>
            <input type="email" name="email" value="{{ old('email') }}" required 
                   class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500">
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Verification Code</label>
            <input type="text" name="otp" required placeholder="Enter 6-digit code" maxlength="6"
                   class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500 text-center text-2xl tracking-widest">
        </div>
        
        <button type="submit" class="w-full bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
            Verify Code
        </button>
    </form>
    
    <p class="mt-4 text-center text-sm text-gray-600">
        <a href="{{ route('login') }}" class="text-blue-600 hover:underline">← Back to Login</a>
    </p>
</div>
@endsection