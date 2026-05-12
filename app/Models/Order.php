<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'order_number',
        'user_id',
        'total_amount',
        'status',
        'delivery_status',
        'estimated_delivery_date',
        'actual_delivery_date',
        'shipping_address',
        'payment_method',
        'payment_status',
        'bank_receipt',
        'notes'
    ];

    protected $casts = [
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
    'estimated_delivery_date' => 'date:Y-m-d',
    'actual_delivery_date' => 'date:Y-m-d'
];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Get delivery status badge HTML
    public function getDeliveryStatusBadgeAttribute()
    {
        switch ($this->delivery_status) {
            case 'pending':
                return '<span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">⏳ Pending</span>';
            case 'processing':
                return '<span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">🔄 Processing</span>';
            case 'shipped':
                return '<span class="bg-purple-100 text-purple-800 px-2 py-1 rounded text-xs">📦 Shipped</span>';
            case 'delivered':
                return '<span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">✅ Delivered</span>';
            case 'cancelled':
                return '<span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs">❌ Cancelled</span>';
            default:
                return '<span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-xs">Unknown</span>';
        }
    }

    
    
    // Get delivery countdown
    public function getDeliveryCountdownAttribute()
    {
        if (!$this->estimated_delivery_date) {
            return null;
        }
        
        $today = Carbon::today();
        $deliveryDate = $this->estimated_delivery_date;
        
        if ($deliveryDate->isPast()) {
            return 'Delivered';
        }
        
        $daysLeft = $today->diffInDays($deliveryDate);
        
        if ($daysLeft == 0) {
            return 'Today';
        } elseif ($daysLeft == 1) {
            return 'Tomorrow';
        } else {
            return $daysLeft . ' days left';
        }
        
    }
}