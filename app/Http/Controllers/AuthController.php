<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        if (strpos($request->email, '@') === false) {
            return back()->withErrors([
                'email' => 'Invalid email or password.',
            ])->onlyInput('email');
        }

        // Check if user exists
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'email' => 'Invalid email or password.',
            ])->onlyInput('email');
        }

        // Skip OTP for admin users
        if ($user->isAdmin()) {
            Auth::login($user);
            $request->session()->regenerate();
            return redirect()->intended('/admin/dashboard');
        }

        // Generate and send OTP
        $otp = $user->generateOtp('login');

        // Store user ID in session temporarily
        session(['temp_user_id' => $user->id, 'login_email' => $user->email]);

        return redirect()->route('verify.login.otp.form')->with('email', $user->email);
    }

    public function showVerifyOtpForm()
    {
        if (!session('temp_user_id')) {
            return redirect()->route('login');
        }
        return view('auth.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6'
        ]);

        $userId = session('temp_user_id');
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Session expired. Please login again.');
        }

        $user = User::find($userId);

        if (!$user || !$user->verifyOtp($request->otp)) {
            return back()->with('error', 'Invalid or expired OTP. Please try again.');
        }

        // Login the user
        Auth::login($user);
        
        // Clear temp session
        session()->forget(['temp_user_id', 'login_email']);

        // Regenerate session
        $request->session()->regenerate();

        if ($user->isAdmin()) {
            return redirect()->intended('/admin/dashboard');
        }

        return redirect()->intended('/');
    }

    public function resendOtp(Request $request)
    {
        $userId = session('temp_user_id');
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Session expired. Please login again.');
        }

        $user = User::find($userId);
        if ($user) {
            $otp = $user->generateOtp('login');
            return back()->with('success', 'New OTP sent to your email!');
        }

        return redirect()->route('login')->with('error', 'User not found.');
    }

    private function sendOtpEmail($email, $otp)
    {
        try {
            Mail::html(
                '<div style="max-width:500px;margin:auto;background:#f4f4f4;padding:20px;font-family:Arial,sans-serif;">
                    <div style="background:#667eea;color:white;padding:20px;text-align:center;">
                        <h2>QuickParts Login Verification</h2>
                    </div>
                    <div style="font-size:32px;font-weight:bold;color:#667eea;text-align:center;padding:20px;background:white;margin:20px 0;letter-spacing:5px;">
                        ' . $otp . '
                    </div>
                    <p style="text-align:center;">This code is valid for 10 minutes.</p>
                    <div style="text-align:center;color:#666;font-size:12px;">
                        <p>If you didn\'t request this, please ignore this email.</p>
                    </div>
                </div>',
                function ($message) use ($email) {
                    $message->to($email)
                            ->subject('QuickParts - Your Login Verification Code');
                }
            );
        } catch (\Exception $e) {
            // Fallback silently - the OTP was already generated and stored
            logger('Failed to send OTP email: ' . $e->getMessage());
        }
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'is_verified' => true
        ]);

        Cart::create(['user_id' => $user->id]);
        Auth::login($user);

        return redirect('/')->with('success', 'Registration successful!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}