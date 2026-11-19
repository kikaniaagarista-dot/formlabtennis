
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings | FORMLAB</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #101010;
            color: white;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 5%;
            background: #080808;
            border-bottom: 1px solid #333;
            flex-wrap: wrap;
            gap: 15px;
        }

        .logo {
            color: #b6ff00;
            font-size: 25px;
            font-weight: bold;
        }

        nav {
            display: flex;
            gap: 25px;
            flex-wrap: wrap;
        }

        nav a {
            color: white;
            text-decoration: none;
            font-size: 14px;
        }

        nav a:hover,
        nav a.active {
            color: #b6ff00;
        }

        .container {
            max-width: 1150px;
            margin: 45px auto;
            padding: 0 22px;
        }

        .page-heading {
            margin-bottom: 28px;
        }

        .page-heading h1 {
            color: #b6ff00;
            font-size: 34px;
            margin-bottom: 10px;
        }

        .page-heading p {
            color: #aaa;
            line-height: 1.6;
        }

        .summary {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #1d211d;
            border: 1px solid #333;
            border-radius: 13px;
            padding: 20px 24px;
            margin-bottom: 25px;
            gap: 15px;
        }

        .summary small {
            color: #aaa;
            display: block;
            margin-bottom: 7px;
        }

        .summary strong {
            color: #b6ff00;
            font-size: 24px;
        }

        .search-panel {
            display: flex;
            gap: 12px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        .search-panel input,
        .search-panel select {
            padding: 13px 15px;
            border-radius: 9px;
            border: 1px solid #ddd;
            background: #fff;
            color: #222;
            font-size: 14px;
            outline: none;
        }

        .search-panel input {
            flex: 1;
            min-width: 200px;
        }

        .search-panel button {
            background: #b6ff00;
            border: none;
            border-radius: 9px;
            padding: 13px 23px;
            font-weight: bold;
            cursor: pointer ;
        }

        .reset-button {
            background: #d91616;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 13px 18px;
            border: 1px solid #d3d0d0;
            border-radius: 9px;
            color: white;
            text-decoration: none;
            font-size: 14px;
            text-align: center;
        }

        .booking-card {
            background: #f5f6f2;
            color: #252525;
            border-radius: 15px;
            padding: 27px;
            margin-bottom: 22px;
            box-shadow: 0 8px 25px rgba(0,0,0,.18);
        }

        .booking-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .booking-header h2 {
            color: #548000;
            font-size: 23px;
            margin-bottom: 7px;
        }

        .booking-id {
            color: #777;
            font-size: 12px;
        }

        .badge {
            display: inline-block;
            padding: 8px 13px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: capitalize;
        }

        .pending {
            background: #fff0c2;
            color: #8a5a00;
        }

        .confirmed {
            background: #d9f4d9;
            color: #24752c;
        }

        .completed {
            background: #dce9ff;
            color: #28569b;
        }

        .cancelled {
            background: #ffe0e0;
            color: #a52d2d;
        }

        .unpaid {
            background: #ffe8cc;
            color: #9a5100;
        }

        .paid {
            background: #d9f4d9;
            color: #24752c;
        }

        .other-status {
            background: #e8e8e8;
            color: #444;
        }

        .booking-price {
            background: #e9f2dd;
            border-radius: 11px;
            padding: 17px 20px;
            margin-bottom: 20px;
        }

        .booking-price small {
            display: block;
            color: #68705e;
            margin-bottom: 6px;
        }

        .booking-price strong {
            color: #385d00;
            font-size: 25px;
        }

        .booking-info {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 13px;
            margin-bottom: 22px;
        }

        .info-item {
            background: #fff;
            border: 1px solid #e6e8e2;
            border-radius: 10px;
            padding: 15px;
        }

        .info-item small {
            display: block;
            color: #888;
            margin-bottom: 7px;
            font-size: 12px;
        }

        .info-item strong {
            color: #333;
            font-size: 14px;
        }

        .schedule {
            border-top: 1px solid #deded9;
            padding-top: 20px;
        }

        .schedule h3 {
            color: #456b00;
            font-size: 18px;
            margin-bottom: 15px;
        }

        .schedule-item {
            background: #fff;
            border: 1px solid #e6e8e2;
            border-radius: 11px;
            padding: 17px;
            margin-bottom: 11px;
        }

        .schedule-date {
            color: #333;
            font-weight: bold;
            margin-bottom: 12px;
        }

        .schedule-detail {
            display: flex;
            flex-wrap: wrap;
            gap: 18px;
            color: #666;
            font-size: 13px;
            line-height: 1.7;
        }

        .booking-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .payment-button {
            display: inline-block;
            background: #b6ff00;
            color: #171717;
            text-decoration: none;
            padding: 13px 21px;
            border-radius: 9px;
            font-weight: bold;
            font-size: 13px;
        }

        .payment-button:hover {
            background: #a3e600;
        }

        .empty {
            text-align: center;
            padding: 55px 20px;
            background: #f5f6f2;
            color: #444;
            border-radius: 14px;
        }

        .empty h2 {
            color: #456b00;
            margin-bottom: 10px;
        }

        .empty p {
            color: #777;
            margin-bottom: 20px;
        }

        .empty a {
            display: inline-block;
            background: #b6ff00;
            color: #111;
            padding: 12px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
        }

        .pagination {
            margin-top: 28px;
            color: #ccc;
        }

        .pagination nav {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }

        .pagination nav svg {
            width: 18px;
            height: 18px;
        }

        .pagination nav span,
        .pagination nav a {
            padding: 9px 13px;
            border-radius: 7px;
            font-size: 13px;
        }

        .pagination nav a {
            background: #292d29;
            color: white;
            text-decoration: none;
        }

        .pagination nav a:hover {
            background: #b6ff00;
            color: #111;
        }

        .pagination nav span[aria-current="page"] span {
            background: #b6ff00;
            color: #111;
        }

        .back-button {
            display: inline-block;
            margin-top: 30px;
            color: #b6ff00;
            text-decoration: none;
        }

        @media (max-width: 650px) {
            header {
                flex-direction: column;
                align-items: flex-start;
            }

            nav {
                gap: 15px;
            }

            .container {
                margin-top: 30px;
            }

            .page-heading h1 {
                font-size: 28px;
            }

            .booking-card {
                padding: 19px;
            }

            .booking-info {
                grid-template-columns: 1fr;
            }

            .summary {
                align-items: flex-start;
            }

            .search-panel {
                flex-direction: column;
            }

            .search-panel input,
            .search-panel select,
            .search-panel button,
            .reset-button {
                width: 100%;
            }
        }
    </style>
</head>

<body>

<header>
    <div class="logo">FORMLAB</div>

    <nav>
        <a href="/member">Dashboard</a>
        <a href="{{ route('member.classes') }}">Classes</a>
        <a href="{{ route('member.bookings.index') }}" class="active">My Bookings</a>
    </nav>
</header>

<div class="container">

    <div class="page-heading">
        <h1>My Bookings</h1>
        <p>Lihat, cari, dan pantau seluruh pemesanan kelas tennis kamu.</p>
    </div>

    <div class="summary">
        <div>
            <small>Total Booking Ditemukan</small>
            <strong>{{ $bookings->total() }} Booking</strong>
        </div>
    </div>

    <form action="{{ route('member.bookings.index') }}" method="GET" class="search-panel">
        <input
            type="text"
            name="search"
            placeholder="Cari nama kelas atau paket..."
            value="{{ request('search') }}"
        >

        <select name="status">
            <option value="">Semua Status</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>

        <button type="submit">Cari</button>

        <a href="{{ route('member.bookings.index') }}" class="reset-button">Reset</a>
    </form>

    @forelse ($bookings as $booking)

        <div class="booking-card">

            <div class="booking-header">
                <div>
                    <h2>{{ $booking->class_name }}</h2>
                    <span class="booking-id">Booking #{{ $booking->id }}</span>
                </div>

                <span class="badge {{ in_array($booking->status, ['pending', 'confirmed', 'completed', 'cancelled']) ? $booking->status : 'other-status' }}">
                    {{ ucfirst($booking->status) }}
                </span>
            </div>

            <div class="booking-price">
                <small>Total Pembayaran</small>
                <strong>Rp{{ number_format($booking->total_price, 0, ',', '.') }}</strong>
            </div>

            <div class="booking-info">
                <div class="info-item">
                    <small>Paket Kelas</small>
                    <strong>{{ $booking->membership_package ?? 'Per Meeting' }}</strong>
                </div>

                <div class="info-item">
                    <small>Jumlah Pertemuan</small>
                    <strong>{{ $booking->total_sessions ?? 1 }} Pertemuan</strong>
                </div>

                <div class="info-item">
                    <small>Pembayaran</small>
                    <strong class="badge {{ $booking->payment_status === 'paid' ? 'paid' : ($booking->payment_status === 'unpaid' ? 'unpaid' : 'other-status') }}">
                        {{ ucfirst($booking->payment_status) }}
                    </strong>
                </div>

                <div class="info-item">
                    <small>Metode Paket</small>
                    <strong>{{ str_replace('_', ' ', ucfirst($booking->payment_method)) }}</strong>
                </div>
            </div>

            <div class="schedule">
                <h3>Jadwal Pertemuan</h3>

                @forelse ($booking->schedules as $schedule)

                    <div class="schedule-item">
                        <p class="schedule-date">
                            {{ $schedule->schedule_date->translatedFormat('l, d F Y') }}
                        </p>

                        <div class="schedule-detail">
                            <span>🕒
                                {{ substr($schedule->start_time, 0, 5) }}
                                -
                                {{ substr($schedule->end_time, 0, 5) }}
                            </span>

                            <span>📍
                                {{ $schedule->location->name ?? 'Belum tersedia' }}
                            </span>
                        </div>
                    </div>

                @empty
                    <p>Belum ada jadwal pertemuan.</p>
                @endforelse
            </div>

            @if ($booking->payment_status !== 'paid')
                <div class="booking-actions">
                    <a
                        href="{{ route('member.booking.payment', $booking->id) }}"
                        class="payment-button"
                    >
                        Lihat Pembayaran →
                    </a>
                </div>
            @endif

        </div>

    @empty

        <div class="empty">
            <h2>Belum Ada Booking</h2>
            <p>Tidak ada pemesanan yang sesuai dengan pencarian kamu.</p>
            <a href="{{ route('member.classes') }}">Lihat Daftar Kelas</a>
        </div>

    @endforelse

    <div class="pagination">
        {{ $bookings->links('pagination::simple-tailwind') }}
    </div>

    <a href="{{ route('member.classes') }}" class="back-button">
        ← Kembali ke Daftar Kelas
    </a>

</div>

</body>
</html>
