@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto bg-white rounded-lg shadow p-6">
    <h1 class="text-2xl font-bold mb-6">Register</h1>
    
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
    
    <form method="POST" action="{{ url('/register') }}" id="registerForm">
        @csrf
        
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Name</label>
            <input type="text" name="name" required value="{{ old('name') }}"
                   class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500">
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Email</label>
            <input type="email" name="email" required value="{{ old('email') }}"
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
        
        <!-- Google reCAPTCHA - REQUIRED -->
        <div class="mb-4">
            <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY', '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI') }}"></div>
            <p class="text-xs text-red-500 mt-1">* Required: Please verify you're not a robot</p>
        </div>
        
        <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Register
        </button>
    </form>
    
    <p class="mt-4 text-center text-gray-600">
        Already have an account? <a href="{{ url('/login') }}" class="text-blue-600 hover:underline">Login</a>
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
    
    // Prevent form submission if reCAPTCHA not checked
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        const recaptchaResponse = document.querySelector('[name="g-recaptcha-response"]')?.value;
        if (!recaptchaResponse) {
            e.preventDefault();
            alert('Please verify that you are not a robot by checking the "I\'m not a robot" box.');
            return false;
        }
    });
</script>
@endsection