<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'class_name',
        'booking_date',
        'booking_time',
        'status',
        'total_price',
        'payment_status',
        'payment_method',
        'membership_package',
        'total_sessions',
        'used_sessions',
        'participants',
        'schedule_id',
        'payment_proof',
        'payment_submitted_at',
        'payment_verified_at',
        'payment_rejection_reason',
    ];

    public function schedule()
    {
        return $this->belongsTo(ClassSchedule::class, 'schedule_id');
    }

    public function schedules()
    {
        return $this->belongsToMany(
            ClassSchedule::class,
            'booking_schedules',
            'booking_id',
            'schedule_id'
        )->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

        public function paymentTransactions()
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    public function latestPaymentTransaction()
    {
        return $this->hasOne(PaymentTransaction::class)->latestOfMany();
    }
}
