<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('membership_package')->nullable();
            $table->unsignedInteger('total_sessions')->nullable();
            $table->unsignedInteger('used_sessions')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'membership_package',
                'total_sessions',
                'used_sessions',
            ]);
        });
    }
};