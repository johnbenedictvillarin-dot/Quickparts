@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto bg-white rounded-lg shadow p-6 mt-8">
    <h1 class="text-2xl font-bold mb-2">Register</h1>
    <p class="text-gray-600 mb-6">Enter your email to receive a verification code</p>
    
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {!! session('success') !!}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
    
    <form method="POST" action="{{ route('send.otp') }}">
        @csrf
        
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Email Address</label>
            <input type="email" name="email" required value="{{ old('email') }}"
                   class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500"
                   placeholder="you@example.com">
        </div>
        
        <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Send Verification Code
        </button>
    </form>
    
    <p class="mt-4 text-center text-gray-600">
        Already have an account? <a href="{{ url('/login') }}" class="text-blue-600 hover:underline">Login</a>
    </p>
</div>
@endsection
