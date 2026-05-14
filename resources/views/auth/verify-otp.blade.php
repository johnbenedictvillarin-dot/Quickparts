@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto bg-white rounded-lg shadow p-6">
    <h1 class="text-2xl font-bold mb-6 text-center">Verify Your Identity</h1>
    
    <div class="text-center mb-4">
        <div class="text-5xl mb-3">🔐</div>
        <p class="text-gray-600">We've sent a verification code to:</p>
        <p class="font-bold text-gray-800">{{ session('email') }}</p>
    </div>
    
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
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
    
    <!-- Verify OTP Form -->
    <form method="POST" action="{{ route('verify.otp') }}" class="mb-6">
        @csrf
        
        <div class="mb-4">
            <label class="block text-gray-700 mb-2 text-center">Enter 6-digit OTP Code</label>
            <input type="text" name="otp" required placeholder="000000" maxlength="6" autofocus
                   class="w-full border rounded px-4 py-3 text-center text-2xl tracking-widest focus:outline-none focus:ring-2 focus:ring-blue-500"
                   oninput="this.value = this.value.replace(/[^0-9]/g, '')">
        </div>
        
        <button type="submit" class="w-full bg-blue-500 text-white py-3 rounded-lg hover:bg-blue-600 transition font-semibold">
            Verify & Continue
        </button>
    </form>
    
    <!-- Resend OTP Form -->
    <div class="text-center">
        <form method="POST" action="{{ route('resend.otp') }}" class="inline">
            @csrf
            <button type="submit" class="text-blue-600 hover:underline text-sm">
                Resend Code
            </button>
        </form>
    </div>
    
    <div class="mt-4 text-center">
        <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-700 text-sm">
            ← Back to Login
        </a>
    </div>
    
    <p class="mt-4 text-center text-xs text-gray-400">
        Didn't receive the code? Check your spam folder.
    </p>
    
    <!-- Countdown Timer for Resend -->
    <script>
        // Countdown timer for resend button (optional)
        let resendBtn = document.querySelector('form.inline button');
        let countdown = 60;
        
        if (resendBtn) {
            let timer = setInterval(function() {
                if (countdown <= 0) {
                    resendBtn.disabled = false;
                    resendBtn.textContent = 'Resend Code';
                    clearInterval(timer);
                } else {
                    resendBtn.disabled = true;
                    resendBtn.textContent = `Resend in ${countdown}s`;
                    countdown--;
                }
            }, 1000);
        }
    </script>
</div>
@endsection