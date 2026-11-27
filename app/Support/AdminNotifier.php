<?php

namespace App\Support;

use App\Models\Booking;
use App\Models\User;
use App\Notifications\AdminAlert;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

/**
 * Pengirim notifikasi ke semua akun admin.
 * Kalau pengiriman gagal, booking/pembayaran TIDAK ikut gagal.
 */
class AdminNotifier
{
    public static function newBooking(Booking $booking): void
    {
        self::send(
            'new_booking',
            'New booking #' . $booking->id,
            self::who($booking) . ' booked ' . $booking->class_name
                . ' (' . $booking->participants . ' participant' . ($booking->participants > 1 ? 's' : '') . ')'
                . ' · ' . self::rupiah($booking->total_price) . ' · waiting for payment',
            '/admin/bookings',
        );
    }

    public static function paymentReceived(Booking $booking, ?string $method = null): void
    {
        self::send(
            'payment_paid',
            'Payment received · ' . self::rupiah($booking->total_price),
            self::who($booking) . ' paid booking #' . $booking->id . ' (' . $booking->class_name . ')'
                . ($method ? ' via ' . strtoupper($method) : '') . '. Booking confirmed.',
            '/admin/payments',
        );
    }

    public static function paymentFailed(Booking $booking, string $status): void
    {
        self::send(
            'payment_failed',
            'Payment ' . $status . ' · booking #' . $booking->id,
            self::who($booking) . '\'s payment for ' . $booking->class_name . ' was ' . $status . '.',
            '/admin/payments',
        );
    }

    public static function needsReview(Booking $booking): void
    {
        self::send(
            'needs_review',
            'Check needed · booking #' . $booking->id,
            self::who($booking) . ' paid ' . self::rupiah($booking->total_price)
                . ' after the slot was taken. Please reschedule or refund.',
            '/admin/payments',
        );
    }

    /** @param Collection<int, Booking> $bookings */
    public static function bookingsExpired(Collection $bookings): void
    {
        if ($bookings->isEmpty()) {
            return;
        }

        $ids    = $bookings->pluck('id')->map(fn ($id) => '#' . $id)->implode(', ');
        $people = $bookings->sum('participants');

        self::send(
            'booking_expired',
            $bookings->count() . ' unpaid booking(s) expired',
            'Booking ' . $ids . ' passed the 15-minute limit. '
                . $people . ' slot(s) are available again.',
            '/admin/bookings',
        );
    }

    private static function send(string $type, string $title, string $message, ?string $url): void
    {
        // Dikirim setelah transaksi database selesai, supaya tidak mengganggu proses utama.
        DB::afterCommit(function () use ($type, $title, $message, $url) {
            try {
                $admins = User::where('role', 'admin')->get();

                Notification::send($admins, new AdminAlert($type, $title, $message, $url));
            } catch (\Throwable $e) {
                Log::warning('Admin notification failed', ['error' => $e->getMessage()]);
            }

            // Email (hanya jenis penting, dan hanya kalau alamat email diisi).
            $email = config('admin_notifications.email');

            if ($email && in_array($type, config('admin_notifications.email_types', []), true)) {
                try {
                    Notification::route('mail', $email)
                        ->notify(new AdminAlert($type, $title, $message, $url));
                } catch (\Throwable $e) {
                    Log::warning('Admin email notification failed', ['error' => $e->getMessage()]);
                }
            }
        });
    }

    private static function who(Booking $booking): string
    {
        return $booking->user?->name ?? 'A member';
    }

    private static function rupiah($amount): string
    {
        return 'Rp' . number_format((float) $amount, 0, ',', '.');
    }
}
