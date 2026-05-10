@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto bg-white rounded-lg shadow p-6">
    <h1 class="text-2xl font-bold mb-6">Complete Registration</h1>
    
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        ✓ Email verified! Please complete your registration.
    </div>
    
    <form method="POST" action="{{ route('register.complete.submit') }}">
        @csrf
        
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Email</label>
            <input type="email" value="{{ $email }}" disabled 
                   class="w-full bg-gray-100 border rounded px-3 py-2">
            <input type="hidden" name="email" value="{{ $email }}">
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Full Name</label>
            <input type="text" name="name" required autofocus
                   class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500">
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Password</label>
            <div class="relative">
                <input type="password" id="password" name="password" required 
                       class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500 pr-10">
                <button type="button" onclick="togglePassword('password', 'passwordIcon')" 
                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                    <span id="passwordIcon">👁️</span>
                </button>
            </div>
            @error('password')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Confirm Password</label>
            <div class="relative">
                <input type="password" id="password_confirmation" name="password_confirmation" required 
                       class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500 pr-10">
                <button type="button" onclick="togglePassword('password_confirmation', 'confirmIcon')" 
                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                    <span id="confirmIcon">👁️</span>
                </button>
            </div>
        </div>
        
        <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Complete Registration
        </button>
    </form>
</div>

<script>
    function togglePassword(inputId, iconId) {
        const passwordInput = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.textContent = '🙈';
        } else {
            passwordInput.type = 'password';
            icon.textContent = '👁️';
        }
    }
</script>
@endsection