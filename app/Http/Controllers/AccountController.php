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
        return view('account.settings', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Check if email is being changed
        if ($request->email !== $user->email) {
            $request->validate([
                'email' => 'required|email|unique:users,email,' . $user->id,
            ]);
            
            // Generate OTP for email change
            $user->generateOtp('change_email', $request->email);
            
            session([
                'verify_email' => $user->email,
                'otp_action' => 'change_email',
                'pending_data' => [
                    'name' => $request->name,
                    'new_email' => $request->email
                ]
            ]);
            
            return redirect()->route('verify.account.otp.form')->with('success', 'Verification code sent to your email!');
        }
        
        // Just update name
        $user->name = $request->name;
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

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        // Generate OTP for password change
        $user->generateOtp('change_password');
        
        session([
            'verify_email' => $user->email,
            'otp_action' => 'change_password',
            'pending_data' => [
                'new_password' => $request->new_password
            ]
        ]);
        
        return redirect()->route('verify.account.otp.form')->with('success', 'Verification code sent to your email!');
    }

    public function showVerifyOtpForm()
    {
        if (!session('verify_email')) {
            return redirect()->route('account.settings')->with('error', 'No verification in progress.');
        }
        
        return view('auth.verify-account-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6'
        ]);

        $user = Auth::user();
        
        if (!$user->verifyOtp($request->otp)) {
            return back()->with('error', 'Invalid or expired OTP. Please try again.');
        }

        $pendingData = session('pending_data');
        $action = session('otp_action');

        if ($action === 'change_email' && isset($pendingData['new_email'])) {
            $user->email = $pendingData['new_email'];
            $user->name = $pendingData['name'];
            $user->clearOtp();
            $user->save();
            
            session()->forget(['verify_email', 'otp_action', 'pending_data']);
            return redirect()->route('account.settings')->with('success', 'Email address updated successfully!');
        }
        
        if ($action === 'change_password' && isset($pendingData['new_password'])) {
            $user->password = Hash::make($pendingData['new_password']);
            $user->clearOtp();
            $user->save();
            
            session()->forget(['verify_email', 'otp_action', 'pending_data']);
            return redirect()->route('account.settings')->with('success', 'Password changed successfully!');
        }

        return redirect()->route('account.settings')->with('error', 'Verification failed. Please try again.');
    }

    public function resendOtp(Request $request)
    {
        $user = Auth::user();
        $action = session('otp_action');
        
        if ($action === 'change_email') {
            $newEmail = session('pending_data.new_email');
            $user->generateOtp('change_email', $newEmail);
        } else {
            $user->generateOtp('change_password');
        }
        
        return back()->with('success', 'New verification code sent to your email!');
    }
}