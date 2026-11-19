<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassSchedule extends Model
{
    protected $fillable = [
        'class_id',
        'location_id',
        'schedule_date',
        'start_time',
        'end_time',
        'capacity',
        'note',
        'is_available',
    ];

    protected $casts = [
        'schedule_date' => 'date',
        'is_available' => 'boolean',
    ];

    public function tennisClass(): BelongsTo
    {
        return $this->belongsTo(TennisClass::class, 'class_id');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(TrainingLocation::class, 'location_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'schedule_id');
    }

    public function bookingsMany()
    {
        return $this->belongsToMany(
            Booking::class,
            'booking_schedules',
            'schedule_id',
            'booking_id'
        )->withTimestamps();
    }
}

