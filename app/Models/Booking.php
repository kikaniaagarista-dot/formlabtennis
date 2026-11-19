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
}