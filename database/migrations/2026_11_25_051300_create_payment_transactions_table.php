<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();

            // Booking yang dibayar. Kalau booking dihapus admin,
            // riwayat transaksinya tetap disimpan untuk laporan keuangan.
            $table->foreignId('booking_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Identitas transaksi
            $table->string('order_id')->unique();           // dibuat oleh kita, misal FLB-12-1732512000
            $table->string('transaction_id')->nullable();   // dibuat oleh Midtrans
            $table->string('snap_token')->nullable();       // token untuk membuka jendela bayar

            // Detail pembayaran
            $table->string('payment_type')->nullable();     // gopay / qris
            $table->decimal('gross_amount', 12, 2);         // nominal yang harus dibayar
            $table->string('transaction_status')->default('pending'); // status dari Midtrans
            $table->string('fraud_status')->nullable();

            // Waktu-waktu penting
            $table->timestamp('transaction_time')->nullable(); // kapan transaksi dibuat di Midtrans
            $table->timestamp('expiry_time')->nullable();      // batas waktu bayar
            $table->timestamp('paid_at')->nullable();          // kapan pembayaran berhasil

            // Salinan lengkap pesan terakhir dari Midtrans (untuk cek kalau ada masalah)
            $table->json('raw_notification')->nullable();

            $table->timestamps();

            $table->index('transaction_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};