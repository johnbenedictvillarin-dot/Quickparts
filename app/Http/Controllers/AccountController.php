<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AccountController extends Controller
{
    public function settings()
    {
        $user = Auth::user();
        
        // Return HTML directly instead of looking for a view
        return $this->getAccountSettingsHTML($user);
    }

    private function getAccountSettingsHTML($user)
    {
        // Get session messages safely
        $successMessage = session('success') ? '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">' . htmlspecialchars(session('success')) . '</div>' : '';
        
        $errorMessages = '';
        if ($errors = session('errors')) {
            $errorMessages = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"><ul>';
            foreach ($errors->all() as $error) {
                $errorMessages .= '<li>' . htmlspecialchars($error) . '</li>';
            }
            $errorMessages .= '</ul></div>';
        }
        
        return '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Account Settings - QuickParts</title>
            <script src="https://cdn.tailwindcss.com"></script>
            <style>
                .password-wrapper {
                    position: relative;
                }
                .toggle-password {
                    position: absolute;
                    right: 10px;
                    top: 50%;
                    transform: translateY(-50%);
                    cursor: pointer;
                    user-select: none;
                    font-size: 18px;
                }
            </style>
        </head>
        <body class="bg-gray-100">
            <!-- Navigation -->
            <nav class="bg-white shadow-lg">
                <div class="max-w-7xl mx-auto px-4">
                    <div class="flex justify-between items-center h-16">
                        <div class="flex items-center">
                            <a href="/" class="text-xl font-bold text-gray-800">🏍️ QuickParts</a>
                            <div class="ml-10">
                                <a href="/products" class="text-gray-700 hover:text-gray-900">Products</a>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <a href="/" class="text-gray-700 hover:text-gray-900">Home</a>
                            <form method="POST" action="/logout" class="inline">
                                <input type="hidden" name="_token" value="' . csrf_token() . '">
                                <button type="submit" class="text-gray-700 hover:text-gray-900">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>
            
            <div class="max-w-4xl mx-auto py-8 px-4">
                <div class="bg-white rounded-lg shadow p-6">
                    <h1 class="text-2xl font-bold mb-6">Account Settings</h1>
                    
                    ' . $successMessage . '
                    ' . $errorMessages . '
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Profile Information -->
                        <div>
                            <h2 class="text-xl font-bold mb-4">Profile Information</h2>
                            
                            <form method="POST" action="/account/profile">
                                <input type="hidden" name="_token" value="' . csrf_token() . '">
                                <input type="hidden" name="_method" value="PUT">
                                
                                <div class="mb-4">
                                    <label class="block text-gray-700 mb-2">Name</label>
                                    <input type="text" name="name" value="' . htmlspecialchars($user->name) . '" required 
                                           class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500">
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-gray-700 mb-2">Email</label>
                                    <input type="email" name="email" value="' . htmlspecialchars($user->email) . '" required 
                                           class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500">
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-gray-700 mb-2">Role</label>
                                    <input type="text" value="' . ucfirst($user->role) . '" disabled 
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
                            
                            <form method="POST" action="/account/password">
                                <input type="hidden" name="_token" value="' . csrf_token() . '">
                                <input type="hidden" name="_method" value="PUT">
                                
                                <div class="mb-4">
                                    <label class="block text-gray-700 mb-2">Current Password</label>
                                    <div class="password-wrapper">
                                        <input type="password" id="current_password" name="current_password" required 
                                               class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500 pr-10">
                                        <span class="toggle-password" onclick="togglePasswordVisibility(\'current_password\', this)">
                                            👁️
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-gray-700 mb-2">New Password</label>
                                    <div class="password-wrapper">
                                        <input type="password" id="new_password" name="new_password" required 
                                               class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500 pr-10">
                                        <span class="toggle-password" onclick="togglePasswordVisibility(\'new_password\', this)">
                                            👁️
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-gray-700 mb-2">Confirm New Password</label>
                                    <div class="password-wrapper">
                                        <input type="password" id="new_password_confirmation" name="new_password_confirmation" required 
                                               class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500 pr-10">
                                        <span class="toggle-password" onclick="togglePasswordVisibility(\'new_password_confirmation\', this)">
                                            👁️
                                        </span>
                                    </div>
                                </div>
                                
                                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                                    Change Password
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <div class="mt-8 pt-6 border-t">
                        <h2 class="text-xl font-bold mb-4">Account Information</h2>
                        <div class="bg-gray-50 p-4 rounded">
                            <p><strong>Member since:</strong> ' . $user->created_at->format('F d, Y') . '</p>
                            <p><strong>Account type:</strong> ' . ucfirst($user->role) . '</p>
                            ' . ($user->role == 'user' ? '<p><strong>Total orders:</strong> ' . $user->orders->count() . '</p>' : '') . '
                        </div>
                    </div>
                </div>
            </div>
            
            <script>
                function togglePasswordVisibility(inputId, element) {
                    const passwordInput = document.getElementById(inputId);
                    if (passwordInput.type === "password") {
                        passwordInput.type = "text";
                        element.textContent = "🙈";
                    } else {
                        passwordInput.type = "password";
                        element.textContent = "👁️";
                    }
                }
            </script>
        </body>
        </html>
        ';
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->back()->with('success', 'Password changed successfully!');
    }
}