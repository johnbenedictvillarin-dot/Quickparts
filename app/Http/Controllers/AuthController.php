<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Verify reCAPTCHA
        $recaptchaResponse = $request->input('g-recaptcha-response');
        
        if (empty($recaptchaResponse)) {
            return back()->withErrors(['g-recaptcha-response' => 'Please verify that you are not a robot.'])->onlyInput('email');
        }

        $recaptchaSecret = env('RECAPTCHA_SECRET_KEY', '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe');
        
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $recaptchaSecret,
            'response' => $recaptchaResponse,
            'remoteip' => $request->ip()
        ]);
        
        $result = $response->json();
        
        if (!$result['success']) {
            return back()->withErrors(['g-recaptcha-response' => 'reCAPTCHA verification failed. Please try again.'])->onlyInput('email');
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            
            if (Auth::user()->isAdmin()) {
                return redirect()->intended('/admin/dashboard');
            }
            
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
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

        // Verify reCAPTCHA
        $recaptchaResponse = $request->input('g-recaptcha-response');
        
        if (empty($recaptchaResponse)) {
            return back()->withErrors(['g-recaptcha-response' => 'Please verify that you are not a robot.'])->withInput();
        }

        $recaptchaSecret = env('RECAPTCHA_SECRET_KEY', '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe');
        
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $recaptchaSecret,
            'response' => $recaptchaResponse,
            'remoteip' => $request->ip()
        ]);
        
        $result = $response->json();
        
        if (!$result['success']) {
            return back()->withErrors(['g-recaptcha-response' => 'reCAPTCHA verification failed. Please try again.'])->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
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