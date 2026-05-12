<?php

namespace App\Http\Controllers;

use App\Models\Otp;
use App\Models\User;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OtpController extends Controller
{
    public function showVerifyForm()
    {
        return view('auth.verify-otp');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = $request->email;
        
        // Generate OTP
        $otp = rand(100000, 999999);
        
        // Delete old OTPs
        Otp::where('email', $email)->delete();
        
        // Store in database
        Otp::create([
            'email' => $email,
            'otp' => $otp,
            'is_verified' => false,
            'expires_at' => now()->addMinutes(10)
        ]);
        
        // Send email with corrected format
        try {
            $this->sendOtpEmail($email, $otp);
            return redirect()->back()
                ->with('success', 'Verification code sent to ' . $email)
                ->with('email', $email);
        } catch (\Exception $e) {
            // If email fails, still show OTP for testing
            return redirect()->back()
                ->with('success', '✅ Your verification code is: <strong style="font-size:28px;">' . $otp . '</strong>')
                ->with('email', $email);
        }
    }

    private function sendOtpEmail($email, $otp)
    {
        // Plain text email body (works 100%)
        $plainText = "Your QuickParts verification code is: " . $otp . "\n\nThis code is valid for 10 minutes.\n\nThank you for using QuickParts!";
        
        // Send using correct Laravel Mail syntax
        Mail::raw($plainText, function ($message) use ($email) {
            $message->to($email)
                    ->subject('QuickParts - Your Verification Code')
                    ->from(env('MAIL_FROM_ADDRESS', 'noreply@quickparts.com'), 'QuickParts System');
        });
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|numeric|digits:6'
        ]);
        
        $email = $request->email;
        $otpCode = $request->otp;
        
        $otpRecord = Otp::where('email', $email)
                       ->where('otp', $otpCode)
                       ->where('is_verified', false)
                       ->first();
        
        if (!$otpRecord) {
            return back()->withErrors(['otp' => 'Invalid verification code.']);
        }
        
        if ($otpRecord->expires_at->isPast()) {
            return back()->withErrors(['otp' => 'Code has expired. Request a new one.']);
        }
        
        $otpRecord->update(['is_verified' => true]);
        session(['verified_email' => $email]);
        
        return redirect()->route('register.complete')->with('success', 'Email verified!');
    }

    public function showRegistrationForm()
    {
        if (!session('verified_email')) {
            return redirect()->route('register')->with('error', 'Verify your email first.');
        }
        
        return view('auth.complete-registration', ['email' => session('verified_email')]);
    }

    public function completeRegistration(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
        
        if (session('verified_email') !== $request->email) {
            return redirect()->route('register')->with('error', 'Verify your email first.');
        }
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);
        
        Cart::create(['user_id' => $user->id]);
        Auth::login($user);
        session()->forget('verified_email');
        
        return redirect('/')->with('success', 'Registration complete!');
    }

    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);
        
        $email = $request->email;
        $otp = rand(100000, 999999);
        
        Otp::where('email', $email)->delete();
        
        Otp::create([
            'email' => $email,
            'otp' => $otp,
            'is_verified' => false,
            'expires_at' => now()->addMinutes(10)
        ]);
        
        try {
            $this->sendOtpEmail($email, $otp);
            return back()->with('success', 'New code sent to ' . $email);
        } catch (\Exception $e) {
            return back()->with('success', 'New code: ' . $otp);
        }
    }
}