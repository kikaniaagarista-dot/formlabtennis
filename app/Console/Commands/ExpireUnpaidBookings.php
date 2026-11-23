<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;

class ExpireUnpaidBookings extends Command
{
    protected $signature = 'bookings:expire-unpaid';

    protected $description = 'Automatically expire unpaid bookings after 15 minutes';

    public function handle(): int
    {
        $expiredBookings = Booking::where('status', 'pending')
            ->where('payment_status', 'unpaid')
            ->where('created_at', '<=', now()->subMinutes(15))
            ->get();

        foreach ($expiredBookings as $booking) {
            $booking->update([
                'status' => 'cancelled',
                'payment_status' => 'expired',
            ]);
        }

        $this->info($expiredBookings->count() . ' booking(s) expired.');

        return self::SUCCESS;
    }
}
