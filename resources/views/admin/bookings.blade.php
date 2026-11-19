
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings | FORMLAB Admin</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>

<div class="admin-container">


    <aside class="sidebar">
        <h2>FORMLAB</h2>
        <p>ADMIN PANEL</p>

        <a href="/admin">Dashboard</a>
        <a href="/admin/bookings" class="active">Bookings</a>
        <a href="/admin/members">Members</a>
        <a href="/admin/classes">Classes</a>
        <a href="/admin/coaches">Coaches</a>

        <a href="{{ route('admin.locations.index') }}">Locations</a>
        <a href="{{ route('admin.schedules.index') }}">Schedules</a>

        <a href="/admin/payments">Payments</a>
        <a href="/admin/settings">Settings</a>
        <a href="{{ route('admin.notes.index') }}">Notes</a>

        <a href="/">Back to Website</a>
    </aside>


    <main class="main-content">

        <div class="topbar">
            <h1>Bookings Management</h1>
            <div class="admin-profile">Administrator</div>
        </div>

        <h2>All Bookings</h2>
        <p>Manage and monitor member bookings.</p>

        <form action="/admin/bookings" method="GET" class="booking-search">

            <div class="search-box">
                <input
                    type="text"
                    name="search"
                    placeholder="Cari nama member, kelas, atau tanggal..."
                    value="{{ request('search') }}"
                >
            </div>

            <select name="status">
                <option value="">Semua Status Booking</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>

            <select name="payment_status">
                <option value="">Semua Pembayaran</option>
                <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
            </select>

            <button type="submit">Cari</button>

            <a href="/admin/bookings">Reset</a>

        </form>

        <p class="booking-total">
            Menampilkan {{ $bookings->count() }} dari {{ $bookings->total() }} booking
        </p>


        <div class="booking-table">
            <table>
                <thead>
                    <tr>
                        <th>Member</th>
                        <th>Class</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Price</th>
                        <th>Booking Status</th>
                        <th>Payment</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($bookings as $booking)
                        <tr>
                            <td>{{ $booking->user->name ?? 'Unknown' }}</td>
                            <td>{{ $booking->class_name }}</td>
                            <td>{{ $booking->booking_date }}</td>
                            <td>{{ $booking->booking_time }}</td>
                            <td>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                            <td>{{ ucfirst($booking->status) }}</td>
                            <td>{{ ucfirst($booking->payment_status) }}</td>
                            <td>
                                <form action="{{ route('admin.bookings.update', $booking->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <select name="status">
                                        <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                        <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>

                                    <select name="payment_status">
                                        <option value="unpaid" {{ $booking->payment_status == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                        <option value="paid" {{ $booking->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                                    </select>

                                    <button type="submit">Update</button>
                                </form>

                                <form action="{{ route('admin.bookings.destroy', $booking->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this booking?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center;">
                                No bookings available.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </main>
</div>

<div class="booking-pagination">

    <div>
        Halaman {{ $bookings->currentPage() }}
        dari {{ $bookings->lastPage() }}
    </div>

    <div>
        {{ $bookings->links('pagination::simple-tailwind') }}
    </div>

</div>

</body>
</html>
