<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments | FORMLAB Admin</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    <style>
        .payment-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
        }

        .payment-filters .search-box {
            flex: 1 1 260px;
        }

        .payment-filters input[type="date"] {
            min-width: 150px;
        }

        .date-label {
            font-size: 13px;
            color: #777;
        }

        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
        }

        .badge-paid    { background: #e5f9d0; color: #2f6b00; }
        .badge-pending { background: #fff4d6; color: #8a5a00; }
        .badge-failed  { background: #fde2e2; color: #a12b2b; }

        .method-tag {
            display: inline-block;
            padding: 3px 9px;
            border-radius: 6px;
            background: #eef0f3;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .cell-sub {
            display: block;
            margin-top: 3px;
            font-size: 12px;
            color: #888;
        }

        .order-id {
            font-family: monospace;
            font-size: 12px;
        }

        .review-alert {
            margin: 0 0 20px;
            padding: 12px 16px;
            border-radius: 10px;
            background: #fde2e2;
            color: #a12b2b;
            font-size: 14px;
        }
    </style>
</head>
<body>
<div class="admin-container">

    <aside class="sidebar">
        <h2>FORMLAB</h2>
        <p>ADMIN PANEL</p>

        <a href="/admin">Dashboard</a>
        <a href="/admin/bookings">Bookings</a>
        <a href="/admin/members">Members</a>
        <a href="/admin/classes">Classes</a>
        <a href="/admin/coaches">Coaches</a>

        <a href="{{ route('admin.locations.index') }}">Locations</a>
        <a href="{{ route('admin.schedules.index') }}">Schedules</a>

        <a href="/admin/payments" class="active">Payments</a>
        <a href="/admin/settings">Settings</a>
        <a href="{{ route('admin.notes.index') }}">Notes</a>

        <a href="/">Back to Website</a>
    </aside>

    <main class="main-content">
        <div class="topbar">
            <h1>Payments Management</h1>
            <div class="admin-profile">Administrator</div>
        </div>

        <h2>GoPay &amp; QRIS Payments</h2>
        <p>Every payment processed automatically through Midtrans. Times are shown in WITA.</p>

        {{-- RINGKASAN --}}
        <div class="stats-grid">
            <div class="stat-card">
                <p>Total Income</p>
                <h2>Rp {{ number_format($summary['income'], 0, ',', '.') }}</h2>
            </div>
            <div class="stat-card">
                <p>Successful</p>
                <h2>{{ $summary['paid'] }}</h2>
            </div>
            <div class="stat-card">
                <p>Waiting for Payment</p>
                <h2>{{ $summary['pending'] }}</h2>
            </div>
            <div class="stat-card">
                <p>Failed / Expired</p>
                <h2>{{ $summary['failed'] }}</h2>
            </div>
        </div>

        @php
            $needsReview = $payments->getCollection()
                ->filter(fn ($p) => $p->booking && $p->booking->status === 'needs_review');
        @endphp

        @if($needsReview->isNotEmpty())
            <div class="review-alert">
                {{ $needsReview->count() }} payment(s) arrived after the booking slot was taken.
                Please check booking
                @foreach($needsReview as $p)
                    #{{ $p->booking_id }}{{ $loop->last ? '' : ',' }}
                @endforeach
                and arrange a reschedule or refund.
            </div>
        @endif

        {{-- FILTER & SEARCH --}}
        <form action="/admin/payments" method="GET" class="booking-search payment-filters">

            <div class="search-box">
                <input
                    type="text"
                    name="search"
                    placeholder="Search member, email, class, Order ID, or booking #..."
                    value="{{ request('search') }}"
                >
            </div>

            <select name="status">
                <option value="">All Status</option>
                <option value="paid"    {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                <option value="failed"  {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
            </select>

            <select name="method">
                <option value="">All Methods</option>
                <option value="gopay" {{ request('method') == 'gopay' ? 'selected' : '' }}>GoPay</option>
                <option value="qris"  {{ request('method') == 'qris' ? 'selected' : '' }}>QRIS</option>
            </select>

            <span class="date-label">From</span>
            <input type="date" name="from" value="{{ request('from') }}">

            <span class="date-label">To</span>
            <input type="date" name="to" value="{{ request('to') }}">

            <button type="submit">Search</button>
            <a href="/admin/payments">Reset</a>
        </form>

        <p class="booking-total">
            Showing {{ $payments->count() }} of {{ $payments->total() }} transactions
        </p>

        {{-- TABEL --}}
        <div class="booking-table">
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Member</th>
                        <th>Class &amp; Schedule</th>
                        <th>Participants</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th>Time (WITA)</th>
                        <th>Order ID</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($payments as $payment)
                        @php
                            $booking = $payment->booking;
                            [$statusLabel, $statusClass] = \App\Http\Controllers\Admin\PaymentController::statusBadge($payment->transaction_status);
                            $time = $payment->paid_at ?? $payment->created_at;
                        @endphp

                        <tr>
                            <td>{{ $payments->firstItem() + $loop->index }}</td>

                            <td>
                                {{ $booking?->user?->name ?? 'Unknown' }}
                                <span class="cell-sub">{{ $booking?->user?->email ?? '-' }}</span>
                            </td>

                            <td>
                                {{ $booking?->class_name ?? 'Booking deleted' }}
                                @if($booking)
                                    <span class="cell-sub">
                                        Booking #{{ $booking->id }}
                                        · {{ $booking->booking_date }} {{ \Illuminate\Support\Str::of($booking->booking_time)->limit(5, '') }}
                                        @if($booking->total_sessions > 1)
                                            · {{ $booking->total_sessions }} sessions
                                        @endif
                                    </span>
                                @endif
                            </td>

                            <td>{{ $booking?->participants ?? '-' }}</td>

                            <td>Rp {{ number_format($payment->gross_amount, 0, ',', '.') }}</td>

                            <td>
                                @if($payment->payment_type)
                                    <span class="method-tag">{{ $payment->payment_type }}</span>
                                @else
                                    -
                                @endif
                            </td>

                            <td>
                                <span class="badge {{ $statusClass }}">{{ $statusLabel }}</span>
                                @if($booking && $booking->status === 'needs_review')
                                    <span class="cell-sub" style="color:#a12b2b;">Needs review</span>
                                @endif
                            </td>

                            <td>
                                {{ $time->copy()->timezone($timezone)->format('d M Y') }}
                                <span class="cell-sub">
                                    {{ $time->copy()->timezone($timezone)->format('H:i') }}
                                    {{ $payment->paid_at ? '· paid' : '· created' }}
                                </span>
                            </td>

                            <td>
                                <span class="order-id">{{ $payment->order_id }}</span>
                                @if($payment->transaction_id)
                                    <span class="cell-sub order-id">{{ \Illuminate\Support\Str::limit($payment->transaction_id, 13) }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align:center;">
                                No transactions found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="booking-pagination">
            <div>
                Page {{ $payments->currentPage() }} of {{ $payments->lastPage() }}
            </div>
            <div>
                {{ $payments->links('pagination::simple-tailwind') }}
            </div>
        </div>
    </main>

</div>
</body>
</html>