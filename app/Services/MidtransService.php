<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\ClassSchedule;
use App\Models\PaymentTransaction;
use DomainException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

class MidtransService
{
    /** Batas waktu bayar sejak booking dibuat (menit). Sama dengan ExpireUnpaidBookings. */
    public const PAYMENT_WINDOW_MINUTES = 15;

    public function __construct()
    {
        Config::$serverKey    = config('midtrans.server_key');
        Config::$clientKey    = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    /**
     * Batas akhir pembayaran untuk sebuah booking.
     */
    public function bookingDeadline(Booking $booking): Carbon
    {
        return $booking->created_at->copy()->addMinutes(self::PAYMENT_WINDOW_MINUTES);
    }

    /**
     * Ambil transaksi pending yang masih bisa dipakai,
     * atau buat transaksi Snap baru ke Midtrans.
     */
    public function getOrCreateSnapTransaction(Booking $booking): PaymentTransaction
    {
        $deadline = $this->bookingDeadline($booking);

        // 1. Kalau masih ada transaksi pending yang belum kedaluwarsa, pakai lagi.
        //    Ini mencegah satu booking punya banyak transaksi aktif sekaligus.
        $existing = $booking->paymentTransactions()
            ->where('transaction_status', 'pending')
            ->whereNotNull('snap_token')
            ->where('expiry_time', '>', now())
            ->latest()
            ->first();

        if ($existing) {
            return $existing;
        }

        // 2. Hitung sisa waktu. Midtrans butuh minimal 1 menit.
        $remainingMinutes = (int) floor(now()->diffInSeconds($deadline, false) / 60);

        if ($remainingMinutes < 1) {
            throw new DomainException('Waktu pembayaran untuk booking ini sudah habis.');
        }

        // 3. Siapkan data transaksi.
        $orderId     = 'FLB-' . $booking->id . '-' . now()->format('YmdHis');
        $grossAmount = (int) round($booking->total_price);
        $startTime   = now();
        $user        = $booking->user;

        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $grossAmount,
            ],
            'item_details' => [[
                'id'       => 'BOOKING-' . $booking->id,
                'price'    => $grossAmount,
                'quantity' => 1,
                'name'     => mb_substr($booking->class_name . ' (' . $booking->participants . ' peserta)', 0, 50),
            ]],
            'customer_details' => [
                'first_name' => $user?->name,
                'email'      => $user?->email,
            ],
            // Hanya tampilkan GoPay dan QRIS.
            'enabled_payments' => ['gopay', 'other_qris'],
            // Transaksi Midtrans ikut habis bersamaan dengan timer booking.
            'expiry' => [
                'start_time' => $startTime->format('Y-m-d H:i:s O'),
                'unit'       => 'minutes',
                'duration'   => $remainingMinutes,
            ],
        ];

        // 4. Minta Snap token ke Midtrans.
        $response = Snap::createTransaction($params);

        // 5. Simpan ke database.
        return PaymentTransaction::create([
            'booking_id'         => $booking->id,
            'order_id'           => $orderId,
            'snap_token'         => $response->token,
            'gross_amount'       => $grossAmount,
            'transaction_status' => 'pending',
            'transaction_time'   => $startTime,
            'expiry_time'        => $startTime->copy()->addMinutes($remainingMinutes),
        ]);
    }

        /* =====================================================================
     |  WEBHOOK (Tahap 5)
     * ===================================================================== */

    /**
     * Ambil status transaksi LANGSUNG dari server Midtrans.
     * Ini sumber kebenaran: isi webhook tidak dipercaya begitu saja.
     */
    public function fetchStatus(string $orderId): array
    {
        $response = Transaction::status($orderId);

        return json_decode(json_encode($response), true) ?? [];
    }

    /**
     * Cek tanda tangan (signature) webhook.
     * Rumus Midtrans: SHA512(order_id + status_code + gross_amount + kunci).
     * Dicoba dengan Signature Secret Key dan Server Key.
     */
    public function signatureMatches(array $payload): bool
    {
        $signature = $payload['signature_key'] ?? '';

        if ($signature === '') {
            return false;
        }

        $base = ($payload['order_id'] ?? '')
            . ($payload['status_code'] ?? '')
            . ($payload['gross_amount'] ?? '');

        foreach ([config('midtrans.signature_key'), config('midtrans.server_key')] as $key) {
            if ($key && hash_equals(hash('sha512', $base . $key), $signature)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Terapkan status dari Midtrans ke database.
     * Aman dipanggil berkali-kali untuk order yang sama.
     */
    public function applyStatus(array $status): string
    {
        return DB::transaction(function () use ($status) {
            $transaction = PaymentTransaction::where('order_id', $status['order_id'] ?? '')
                ->lockForUpdate()
                ->first();

            if (! $transaction) {
                return 'unknown_order';
            }

            $txStatus = $status['transaction_status'] ?? 'unknown';
            $fraud    = $status['fraud_status'] ?? null;

            // 1. Nominal harus sama persis dengan yang kita simpan.
            $paidAmount     = (int) round((float) ($status['gross_amount'] ?? 0));
            $expectedAmount = (int) round((float) $transaction->gross_amount);

            if ($paidAmount !== $expectedAmount) {
                Log::warning('Midtrans: nominal tidak cocok', [
                    'order_id' => $transaction->order_id,
                    'expected' => $expectedAmount,
                    'received' => $paidAmount,
                ]);

                $transaction->update(['raw_notification' => $status]);

                return 'amount_mismatch';
            }

            $isPaid = $txStatus === 'settlement'
                || ($txStatus === 'capture' && $fraud === 'accept');

            // 2. Transaksi yang sudah lunas tidak boleh turun status.
            if ($transaction->paid_at && ! $isPaid) {
                $transaction->update(['raw_notification' => $status]);

                return 'already_paid';
            }

            // 3. Simpan status terbaru transaksi.
            $transaction->update([
                'transaction_id'     => $status['transaction_id'] ?? $transaction->transaction_id,
                'payment_type'       => $status['payment_type'] ?? $transaction->payment_type,
                'transaction_status' => $txStatus,
                'fraud_status'       => $fraud,
                'paid_at'            => $isPaid ? ($transaction->paid_at ?? now()) : null,
                'raw_notification'   => $status,
            ]);

            if (! $isPaid || ! $transaction->booking_id) {
                // pending / expire / cancel / deny / failure:
                // booking dibiarkan; pembatalan booking tetap diurus
                // oleh perintah bookings:expire-unpaid (15 menit).
                return 'updated';
            }

            // 4. Pembayaran berhasil → perbarui booking.
            $booking = Booking::lockForUpdate()->find($transaction->booking_id);

            if (! $booking) {
                return 'updated';
            }

            if ($booking->payment_status === 'paid') {
                return 'already_paid';
            }

            $paidData = [
                'payment_status'      => 'paid',
                'payment_verified_at' => now(),
            ];

            if ($booking->status === 'pending') {
                $booking->update($paidData + ['status' => 'confirmed']);

                return 'paid';
            }

            // 5. Kasus langka: booking sudah dibatalkan timer, lalu uang masuk.
            //    Kalau slot masih cukup → aktifkan lagi. Kalau tidak → admin cek manual.
            if ($this->bookingStillFits($booking)) {
                $booking->update($paidData + ['status' => 'confirmed']);

                return 'paid_restored';
            }

            $booking->update($paidData + ['status' => 'needs_review']);

            Log::warning('Midtrans: pembayaran masuk tapi slot sudah penuh', [
                'booking_id' => $booking->id,
                'order_id'   => $transaction->order_id,
            ]);

            return 'needs_review';
        });
    }

    /**
     * Apakah semua jadwal booking ini masih punya slot untuk pesertanya?
     */
    private function bookingStillFits(Booking $booking): bool
    {
        $scheduleIds = $booking->schedules()->pluck('class_schedules.id');

        $schedules = ClassSchedule::whereIn('id', $scheduleIds)->lockForUpdate()->get();

        foreach ($schedules as $schedule) {
            $booked = DB::table('booking_schedules')
                ->join('bookings', 'booking_schedules.booking_id', '=', 'bookings.id')
                ->where('booking_schedules.schedule_id', $schedule->id)
                ->where('bookings.status', '!=', 'cancelled')
                ->where('bookings.id', '!=', $booking->id)
                ->sum('bookings.participants');

            if ($booking->participants > $schedule->capacity - $booked) {
                return false;
            }
        }

        return true;
    }
}