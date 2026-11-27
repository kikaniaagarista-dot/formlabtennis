<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\MidtransService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use DomainException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PaymentController extends Controller
{
    /**
     * Dipanggil saat member menekan tombol "Bayar dengan GoPay / QRIS".
     * Mengembalikan snap_token dalam bentuk JSON.
     */
    public function createSnapToken($id, MidtransService $midtrans)
    {        // Mode uji coba: hanya admin yang boleh memakai Midtrans.
        if (config('midtrans.admin_only') && Auth::user()->role !== 'admin') {
            abort(403);
        }

        try {
            $transaction = DB::transaction(function () use ($id, $midtrans) {
                // Kunci baris booking supaya klik dua kali tidak membuat dua transaksi.
                $booking = Booking::where('user_id', Auth::id())
                    ->lockForUpdate()
                    ->findOrFail($id);

                if ($booking->status !== 'pending' || $booking->payment_status !== 'unpaid') {
                    throw new DomainException('Booking ini tidak sedang menunggu pembayaran.');
                }

                return $midtrans->getOrCreateSnapTransaction($booking);
            });
        } catch (ModelNotFoundException $e) {
            abort(404);
        } catch (DomainException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        } catch (\Throwable $e) {
            Log::error('Midtrans Snap gagal dibuat', [
                'booking_id' => $id,
                'error'      => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Gagal menghubungi layanan pembayaran. Silakan coba lagi.',
            ], 500);
        }

        return response()->json([
            'snap_token' => $transaction->snap_token,
            'order_id'   => $transaction->order_id,
        ]);
    }
}