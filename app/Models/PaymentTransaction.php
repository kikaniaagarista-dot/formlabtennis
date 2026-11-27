<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    protected $fillable = [
        'booking_id',
        'order_id',
        'transaction_id',
        'snap_token',
        'payment_type',
        'gross_amount',
        'transaction_status',
        'fraud_status',
        'transaction_time',
        'expiry_time',
        'paid_at',
        'raw_notification',
    ];

    protected $casts = [
        'gross_amount'     => 'decimal:2',
        'transaction_time' => 'datetime',
        'expiry_time'      => 'datetime',
        'paid_at'          => 'datetime',
        'raw_notification' => 'array',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}