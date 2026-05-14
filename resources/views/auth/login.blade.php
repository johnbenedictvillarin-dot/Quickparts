@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto bg-white rounded-lg shadow p-6">
    <h1 class="text-2xl font-bold mb-6 text-center">Login</h1>
    
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
    
    <form method="POST" action="{{ url('/login') }}" id="loginForm">
        @csrf
        
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required 
                   class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500">
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
        </div>
        
        <div class="mb-4">
            <label class="flex items-center">
                <input type="checkbox" name="remember" class="mr-2">
                <span class="text-gray-700">Remember Me</span>
            </label>
        </div>
        
        <!-- reCAPTCHA -->
        <div class="mb-4 flex justify-center">
            <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY', '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI') }}"></div>
        </div>
        
        <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Login
        </button>
    </form>
    
    <p class="mt-4 text-center">
        Don't have an account? <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Register</a>
    </p>
</div>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>
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