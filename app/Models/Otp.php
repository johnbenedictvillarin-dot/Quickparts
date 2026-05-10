<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    use HasFactory;

    protected $table = 'otps';
    
    protected $fillable = [
        'email',
        'otp',
        'is_verified',
        'expires_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_verified' => 'boolean'
    ];

    public function isValid()
    {
        return !$this->is_verified && $this->expires_at->isFuture();
    }
}