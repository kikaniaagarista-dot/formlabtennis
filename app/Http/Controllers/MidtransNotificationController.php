<?php

namespace App\Http\Controllers;

use App\Models\PaymentTransaction;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransNotificationController extends Controller
{
    /**
     * Alamat yang dipanggil server Midtrans setiap status pembayaran berubah.
     * POST /midtrans/notification
     */
    public function __invoke(Request $request, MidtransService $midtrans)
    {
        $payload = $request->all();
        $orderId = (string) ($payload['order_id'] ?? '');

        // 1. Order yang bukan milik kita (misalnya tes dari dashboard) → abaikan.
        if ($orderId === '' || ! PaymentTransaction::where('order_id', $orderId)->exists()) {
            Log::info('Midtrans: notifikasi untuk order tidak dikenal', ['order_id' => $orderId]);

            return response()->json(['message' => 'ignored']);
        }

        // 2. Cek tanda tangan. Kalau tidak cocok, dicatat,
        //    tapi status tetap dicek ulang langsung ke Midtrans di langkah 3.
        if (! $midtrans->signatureMatches($payload)) {
            Log::warning('Midtrans: signature tidak cocok', ['order_id' => $orderId]);
        }

        // 3. Tanya status asli ke server Midtrans (bukan percaya isi webhook).
        try {
            $status = $midtrans->fetchStatus($orderId);
        } catch (\Throwable $e) {
            Log::error('Midtrans: gagal cek status', [
                'order_id' => $orderId,
                'error'    => $e->getMessage(),
            ]);

            // 500 → Midtrans akan mengirim ulang notifikasi nanti.
            return response()->json(['message' => 'retry later'], 500);
        }

        if (($status['order_id'] ?? null) !== $orderId) {
            return response()->json(['message' => 'ignored']);
        }

        // 4. Terapkan ke database.
        $result = $midtrans->applyStatus($status);

        Log::info('Midtrans: notifikasi diproses', [
            'order_id' => $orderId,
            'status'   => $status['transaction_status'] ?? null,
            'result'   => $result,
        ]);

        return response()->json(['message' => 'ok', 'result' => $result]);
    }
}