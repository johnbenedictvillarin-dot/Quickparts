@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold mb-6">Account Settings</h1>
        
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Profile Information -->
            <div>
                <h2 class="text-xl font-bold mb-4">Profile Information</h2>
                
                <form method="POST" action="{{ route('account.update.profile') }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required 
                               class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required 
                               class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500">
                        <p class="text-xs text-gray-500 mt-1">Changing email requires OTP verification</p>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Role</label>
                        <input type="text" value="{{ ucfirst($user->role) }}" disabled 
                               class="w-full bg-gray-100 border rounded px-3 py-2">
                    </div>
                    
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Update Profile
                    </button>
                </form>
            </div>
            
            <!-- Change Password -->
            <div>
                <h2 class="text-xl font-bold mb-4">Change Password</h2>
                
                <form method="POST" action="{{ route('account.update.password') }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Current Password</label>
                        <div class="relative">
                            <input type="password" id="current_password" name="current_password" required 
                                   class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500 pr-10">
                            <button type="button" onclick="togglePassword('current_password', 'currentIcon')" 
                                    class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                <span id="currentIcon">👁️</span>
                            </button>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">New Password</label>
                        <div class="relative">
                            <input type="password" id="new_password" name="new_password" required 
                                   class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500 pr-10">
                            <button type="button" onclick="togglePassword('new_password', 'newIcon')" 
                                    class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                <span id="newIcon">👁️</span>
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Changing password requires OTP verification</p>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Confirm New Password</label>
                        <div class="relative">
                            <input type="password" id="new_password_confirmation" name="new_password_confirmation" required 
                                   class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500 pr-10">
                            <button type="button" onclick="togglePassword('new_password_confirmation', 'confirmIcon')" 
                                    class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                <span id="confirmIcon">👁️</span>
                            </button>
                        </div>
                    </div>
                    
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                        Change Password
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Account Information -->
        <div class="mt-8 pt-6 border-t">
            <h2 class="text-xl font-bold mb-4">Account Information</h2>
            <div class="bg-gray-50 p-4 rounded">
                <p><strong>Member since:</strong> {{ $user->created_at->format('F d, Y') }}</p>
                <p><strong>Account type:</strong> {{ ucfirst($user->role) }}</p>
                @if($user->role == 'user')
                    <p><strong>Total orders:</strong> {{ $user->orders->count() }}</p>
                @endif
            </div>
        </div>
    </div>
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