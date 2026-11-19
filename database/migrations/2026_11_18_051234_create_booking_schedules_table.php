<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_schedules', function (Blueprint $table) {
            $table->id();

            $table->foreignId('booking_id')
                ->constrained('bookings')
                ->cascadeOnDelete();

            $table->foreignId('schedule_id')
                ->constrained('class_schedules')
                ->restrictOnDelete();

            $table->timestamps();

            $table->unique(['booking_id', 'schedule_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_schedules');
    }
};