<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'otp',
        'otp_expires_at',
        'pending_email',
        'otp_action'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'otp'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'otp_expires_at' => 'datetime'
    ];

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function generateOtp($action, $newEmail = null)
    {
        $this->otp = rand(100000, 999999);
        $this->otp_expires_at = now()->addMinutes(10);
        $this->otp_action = $action;
        
        if ($newEmail) {
            $this->pending_email = $newEmail;
        }
        
        $this->save();
        $this->sendOtpEmail($this->email, $this->otp, $action);
        return $this->otp;
    }

    public function verifyOtp($otp)
    {
        if ($this->otp === $otp && $this->otp_expires_at && now()->lessThan($this->otp_expires_at)) {
            return true;
        }
        return false;
    }

    public function clearOtp()
    {
        $this->otp = null;
        $this->otp_expires_at = null;
        $this->otp_action = null;
        $this->save();
    }

    public function completeEmailChange()
    {
        if ($this->pending_email) {
            $this->email = $this->pending_email;
            $this->pending_email = null;
            $this->save();
            return true;
        }
        return false;
    }

    private function sendOtpEmail($email, $otp, $action)
    {
        try {
            $actionTexts = [
                'login' => 'login to your account',
                'change_email' => 'change your email address',
                'password_reset' => 'reset your password',
            ];
            $actionText = $actionTexts[$action] ?? 'complete this action';
            $subject = 'QuickParts - Your OTP Verification Code';

            Mail::html(
                '<div style="max-width:500px;margin:0 auto;background:white;border-radius:10px;overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,0.1);font-family:Arial,sans-serif;">
                    <div style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:white;padding:30px;text-align:center;">
                        <h2>QuickParts Verification</h2>
                    </div>
                    <div style="padding:30px;text-align:center;">
                        <h3>Hello ' . $this->name . '!</h3>
                        <p>You requested to ' . $actionText . '.</p>
                        <p>Your verification code is:</p>
                        <div style="font-size:36px;font-weight:bold;color:#667eea;letter-spacing:5px;padding:15px;background:#f0f0f0;border-radius:8px;margin:20px 0;font-family:monospace;">' . $otp . '</div>
                        <p>This code is valid for <strong>10 minutes</strong>.</p>
                        <p>If you didn\'t request this, please ignore this email.</p>
                    </div>
                    <div style="background:#f8f9fa;padding:20px;text-align:center;color:#666;font-size:12px;">
                        <p>&copy; ' . date('Y') . ' QuickParts. All rights reserved.</p>
                    </div>
                </div>',
                function ($message) use ($email, $subject) {
                    $message->to($email)->subject($subject);
                }
            );
        } catch (\Exception $e) {
            logger('Failed to send OTP email: ' . $e->getMessage());
        }
    }
}