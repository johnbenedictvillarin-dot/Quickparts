@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto bg-white rounded-lg shadow p-6">
    <h1 class="text-2xl font-bold mb-6 text-center">Verify Your Identity</h1>
    
    <div class="text-center mb-4">
        <div class="text-5xl mb-3">🔐</div>
        <p class="text-gray-600">A verification code has been sent to:</p>
        <p class="font-bold text-gray-800">{{ session('verify_email') }}</p>
    </div>
    
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4 text-center">
        <p class="text-sm text-blue-700">
            <strong>Action:</strong> {{ session('otp_action') === 'change_email' ? 'Changing Email Address' : 'Changing Password' }}
        </p>
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
    
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
    
    <form method="POST" action="{{ route('verify.account.otp') }}">
        @csrf
        
        <div class="mb-4">
            <label class="block text-gray-700 mb-2 text-center">Enter 6-digit Code</label>
            <input type="text" name="otp" required placeholder="123456" maxlength="6" autofocus
                   class="w-full border rounded px-4 py-3 text-center text-2xl tracking-widest focus:outline-none focus:ring-2 focus:ring-blue-500"
                   oninput="this.value = this.value.replace(/[^0-9]/g, '')">
        </div>
        
        <button type="submit" class="w-full bg-blue-500 text-white py-3 rounded-lg hover:bg-blue-600 transition font-semibold">
            Verify & Continue
        </button>
    </form>
    
    <div class="text-center mt-4">
        <form method="POST" action="{{ route('resend.account.otp') }}" class="inline">
            @csrf
            <button type="submit" class="text-blue-600 hover:underline text-sm">
                Resend Code
            </button>
        </form>
    </div>
    
    <div class="mt-4 text-center">
        <a href="{{ route('account.settings') }}" class="text-gray-500 hover:text-gray-700 text-sm">
            ← Back to Account Settings
        </a>
    </div>
</div>
@endsection