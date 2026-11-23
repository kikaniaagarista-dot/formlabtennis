<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ClassSchedule;

class DeletePastSchedules extends Command
{
    protected $signature = 'schedules:delete-past';

    protected $description = 'Delete past schedules that have no bookings';

    public function handle(): int
    {
        $deleted = ClassSchedule::whereDate('schedule_date', '<', today())
            ->whereDoesntHave('bookings')
            ->whereDoesntHave('bookingsMany')
            ->delete();

        $this->info($deleted . ' past schedule(s) deleted.');

        return self::SUCCESS;
    }
}
