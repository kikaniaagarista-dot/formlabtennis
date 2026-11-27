<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PaymentController extends Controller
{
    /** Zona waktu tampilan admin (database menyimpan UTC). */
    public const TIMEZONE = 'Asia/Makassar';

    /** Kelompok status Midtrans → label untuk admin. */
    public const STATUS_GROUPS = [
        'paid'    => ['settlement', 'capture'],
        'pending' => ['pending'],
        'expired' => ['expire'],
        'failed'  => ['cancel', 'deny', 'failure'],
    ];

    public function index(Request $request)
    {
        $query = $this->filteredQuery($request);

        // Kartu ringkasan, dihitung dari hasil filter yang sama.
        $summary = [
            'income'  => (clone $query)->whereIn('transaction_status', self::STATUS_GROUPS['paid'])->sum('gross_amount'),
            'paid'    => (clone $query)->whereIn('transaction_status', self::STATUS_GROUPS['paid'])->count(),
            'pending' => (clone $query)->whereIn('transaction_status', self::STATUS_GROUPS['pending'])->count(),
            'failed'  => (clone $query)->whereIn('transaction_status', [
                ...self::STATUS_GROUPS['expired'],
                ...self::STATUS_GROUPS['failed'],
            ])->count(),
        ];

        $payments = $query
            ->with(['booking.user'])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.payments', [
            'payments' => $payments,
            'summary'  => $summary,
            'timezone' => self::TIMEZONE,
        ]);
    }

    /**
     * Query transaksi sesuai filter yang diisi admin.
     */
    private function filteredQuery(Request $request)
    {
        $query = PaymentTransaction::query();

        // Cari: nama member, email, kelas, Order ID, atau ID booking.
        if ($search = trim((string) $request->query('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('order_id', 'like', "%{$search}%")
                    ->orWhere('transaction_id', 'like', "%{$search}%")
                    ->orWhereHas('booking', function ($b) use ($search) {
                        $b->where('class_name', 'like', "%{$search}%")
                            ->orWhere('id', ltrim($search, '#'))
                            ->orWhereHas('user', function ($u) use ($search) {
                                $u->where('name', 'like', "%{$search}%")
                                    ->orWhere('email', 'like', "%{$search}%");
                            });
                    });
            });
        }

        // Status: paid / pending / expired / failed
        $status = $request->query('status');
        if (isset(self::STATUS_GROUPS[$status])) {
            $query->whereIn('transaction_status', self::STATUS_GROUPS[$status]);
        }

        // Metode: gopay / qris
        if (in_array($request->query('method'), ['gopay', 'qris'], true)) {
            $query->where('payment_type', $request->query('method'));
        }

        // Rentang tanggal (tanggal WITA, dikonversi ke UTC untuk database).
        if ($from = $this->parseDate($request->query('from'))) {
            $query->where('created_at', '>=', $from->startOfDay()->utc());
        }

        if ($to = $this->parseDate($request->query('to'))) {
            $query->where('created_at', '<=', $to->endOfDay()->utc());
        }

        return $query;
    }

    private function parseDate($value): ?Carbon
    {
        if (! $value) {
            return null;
        }

        try {
            return Carbon::createFromFormat('Y-m-d', $value, self::TIMEZONE);
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Label dan warna status untuk tampilan.
     */
    public static function statusBadge(?string $status): array
    {
        foreach (self::STATUS_GROUPS as $group => $statuses) {
            if (in_array($status, $statuses, true)) {
                return match ($group) {
                    'paid'    => ['Paid', 'badge-paid'],
                    'pending' => ['Pending', 'badge-pending'],
                    'expired' => ['Expired', 'badge-failed'],
                    'failed'  => ['Failed', 'badge-failed'],
                };
            }
        }

        return [ucfirst((string) $status), 'badge-pending'];
    }
}