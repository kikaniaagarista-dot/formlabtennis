
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments | FORMLAB Admin</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
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

        <h2>All Payments</h2>
        <p>Monitor and review member booking payments.</p>

        <form action="/admin/payments" method="GET" class="booking-search">

            <div class="search-box">
                <input
                    type="text"
                    name="search"
                    placeholder="Cari nama member..."
                    value="{{ request('search') }}"
                >
            </div>

            <select name="status">
                <option value="">Semua Status</option>
                <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
            </select>

            <button type="submit">Cari</button>

            <a href="/admin/payments">Reset</a>

        </form>

        <p class="booking-total">
            Menampilkan {{ $payments->count() }} dari {{ $payments->total() }} pembayaran
        </p>

        <div class="booking-table">
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Member</th>
                        <th>Booking</th>
                        <th>Amount</th>
                        <th>Payment Method</th>
                        <th>Payment Status</th>
                        <th>Date</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($payments as $payment)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $payment->user->name ?? 'Unknown' }}</td>
                            <td>{{ $payment->class_name }}</td>
                            <td>
                                Rp {{ number_format($payment->total_price, 0, ',', '.') }}
                            </td>
                            <td>{{ $payment->payment_method ?? '-' }}</td>
                            <td>{{ ucfirst($payment->payment_status) }}</td>
                            <td>{{ $payment->created_at->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center;">
                                No payment records available yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
                <div class="booking-pagination">

                    <div>
                        Halaman {{ $payments->currentPage() }}
                        dari {{ $payments->lastPage() }}
                    </div>

                    <div>
                        {{ $payments->links('pagination::simple-tailwind') }}
                    </div>

                </div>
    </main>

</div>
</body>
</html>
