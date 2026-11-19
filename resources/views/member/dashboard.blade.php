
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | FORMLAB Tennis</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: #10110f;
            color: #fff;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        .layout {
            display: flex;
            min-height: 100vh;
        }

        /* SIDEBAR */
        .sidebar {
            width: 245px;
            min-width: 245px;
            background: #171916;
            border-right: 1px solid #292d25;
            padding: 30px 20px;
            display: flex;
            flex-direction: column;
        }

        .brand {
            font-size: 27px;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .brand span {
            color: #b8ed43;
        }

        .brand-subtitle {
            font-size: 9px;
            color: #858b7e;
            letter-spacing: 2px;
            margin-top: 4px;
        }

        .menu-title {
            font-size: 10px;
            color: #747b6d;
            font-weight: 700;
            letter-spacing: 1.5px;
            margin: 48px 0 15px 10px;
        }

        .menu a {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 14px 13px;
            margin-bottom: 7px;
            border-radius: 9px;
            color: #aeb4a7;
            font-size: 12px;
            transition: .2s;
        }

        .menu a i {
            width: 17px;
            text-align: center;
        }

        .menu a:hover,
        .menu a.active {
            background: #b8ed43;
            color: #171916;
            font-weight: 700;
        }

        .sidebar-promo {
            margin-top: auto;
            height: 190px;
            border-radius: 12px;
            overflow: hidden;
            position: relative;
            background:
                linear-gradient(to top, #11140eea, transparent),
                url('{{ asset('images/sidebar-tennis.png') }}') center/cover;
            display: flex;
            align-items: flex-end;
            padding: 15px;
        }

        .sidebar-promo h4 {
            font-size: 14px;
            line-height: 1.4;
            letter-spacing: .5px;
        }

        .sidebar-promo span {
            color: #b8ed43;
        }

        .sidebar-bottom {
            margin-top: 20px;
            border-top: 1px solid #30342c;
            padding-top: 15px;
        }

        .logout-button {
            width: 100%;
            background: transparent;
            border: none;
            color: #aeb4a7;
            font-family: inherit;
            text-align: left;
            padding: 12px;
            cursor: pointer;
            font-size: 12px;
        }

        .logout-button i {
            margin-right: 12px;
        }

        .logout-button:hover {
            color: #b8ed43;
        }

        /* MAIN */
        .main {
            flex: 1;
            min-width: 0;
            padding: 30px 38px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 27px;
        }

        .topbar-title {
            font-size: 20px;
            font-weight: 800;
        }

        .topbar-subtitle {
            color: #888f81;
            font-size: 11px;
            margin-top: 5px;
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 12px;
            background: #1a1d18;
            border: 1px solid #30352b;
            border-radius: 10px;
            padding: 9px 14px;
        }

        .profile-avatar {
            width: 37px;
            height: 37px;
            border-radius: 50%;
            background: #b8ed43;
            color: #171916;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: 800;
        }

        .profile-name {
            font-size: 12px;
            font-weight: 700;
        }

        .profile-role {
            font-size: 10px;
            color: #899080;
            margin-top: 3px;
        }

        /* HERO */
        .hero {
            min-height: 260px;
            border-radius: 15px;
            overflow: hidden;
            background:
                linear-gradient(90deg, rgba(8,10,8,.94), rgba(8,10,8,.60), rgba(8,10,8,.08)),
                url('{{ asset('images/dashboard-hero.png') }}') center/cover;
            display: flex;
            align-items: center;
            padding: 38px;
            margin-bottom: 23px;
            border: 1px solid #30352b;
        }

        .hero-content {
            max-width: 510px;
        }

        .hero-label {
            color: #b8ed43;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 2px;
            margin-bottom: 14px;
        }

        .hero h1 {
            font-size: clamp(27px, 3vw, 40px);
            line-height: 1.2;
            font-weight: 800;
            margin-bottom: 12px;
        }

        .hero h1 span {
            color: #b8ed43;
        }

        .hero p {
            color: #d0d4cb;
            font-size: 12px;
            line-height: 1.8;
            max-width: 380px;
        }

        .hero-button {
            display: inline-block;
            margin-top: 20px;
            background: #b8ed43;
            color: #171916;
            border-radius: 6px;
            padding: 12px 18px;
            font-size: 11px;
            font-weight: 800;
        }

        .hero-button:hover {
            background: #d0ff65;
        }

        /* SECTION TITLE */
        .section-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
        }

        .section-heading h3 {
            font-size: 15px;
            font-weight: 800;
        }

        .section-heading a {
            font-size: 10px;
            color: #b8ed43;
        }

        /* STATS */
        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: #1a1d18;
            border: 1px solid #30352b;
            border-radius: 12px;
            padding: 19px;
        }

        .stat-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .stat-label {
            font-size: 11px;
            color: #a1a797;
        }

        .stat-icon {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            background: #2a3520;
            color: #b8ed43;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stat-value {
            font-size: 29px;
            font-weight: 800;
        }

        .stat-note {
            color: #7f8875;
            font-size: 10px;
            margin-top: 5px;
        }

        /* PROGRAM BANNER */
        .program-banner {
            min-height: 155px;
            border-radius: 13px;
            overflow: hidden;
            border: 1px solid #353b2c;
            background:
                linear-gradient(90deg, #11140ef2 0%, #11140ec9 45%, transparent 100%),
                url('{{ asset('images/program-banner.png') }}') center 40%/cover;
            display: flex;
            align-items: center;
            padding: 25px 30px;
            margin-bottom: 28px;
        }

        .program-content {
            max-width: 400px;
        }

        .program-label {
            color: #b8ed43;
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 2px;
            margin-bottom: 9px;
        }

        .program-banner h3 {
            font-size: 21px;
            margin-bottom: 7px;
        }

        .program-banner p {
            color: #c4c9bc;
            font-size: 11px;
            line-height: 1.7;
        }

        .program-button {
            display: inline-block;
            margin-top: 14px;
            color: #171916;
            background: #b8ed43;
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 10px;
            font-weight: 800;
        }

        /* BOTTOM GRID */
        .bottom-grid {
            display: grid;
            grid-template-columns: 1.1fr 1fr;
            gap: 17px;
        }

        .panel {
            background: #1a1d18;
            border: 1px solid #30352b;
            border-radius: 12px;
            padding: 19px;
            min-width: 0;
        }

        .panel-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 17px;
        }

        .panel-heading h3 {
            font-size: 14px;
        }

        .panel-heading a {
            color: #b8ed43;
            font-size: 10px;
        }

        .booking-item {
            display: flex;
            gap: 12px;
            padding: 13px 0;
            border-top: 1px solid #30352b;
        }

        .booking-image {
            width: 66px;
            min-width: 66px;
            height: 66px;
            border-radius: 8px;
            background: url('{{ asset('images/schedule-court.jpg') }}') center/cover;
        }

        .booking-info {
            flex: 1;
            min-width: 0;
        }

        .booking-info h4 {
            font-size: 11px;
            margin-bottom: 7px;
        }

        .booking-info p {
            color: #929a88;
            font-size: 10px;
            line-height: 1.8;
        }

        .booking-status {
            display: inline-block;
            background: #303b22;
            color: #c5f16c;
            padding: 5px 8px;
            border-radius: 20px;
            font-size: 9px;
            font-weight: 700;
            white-space: nowrap;
        }

        .booking-status.pending {
            background: #44371e;
            color: #f4c66b;
        }

        .booking-status.cancelled {
            background: #452625;
            color: #ff9991;
        }

        .empty-state {
            border-top: 1px solid #30352b;
            padding: 25px 10px;
            text-align: center;
            color: #929a88;
            font-size: 11px;
            line-height: 1.8;
        }

        .empty-state a {
            color: #b8ed43;
            font-weight: 700;
        }

        /* RESPONSIVE */
        @media (max-width: 1050px) {
            .sidebar {
                width: 210px;
                min-width: 210px;
            }

            .main {
                padding: 25px 22px;
            }

            .bottom-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 700px) {
            .layout {
                display: block;
            }

            .sidebar {
                width: 100%;
                min-width: 0;
                padding: 20px;
            }

            .menu-title {
                margin-top: 22px;
            }

            .menu {
                display: flex;
                flex-wrap: wrap;
                gap: 5px;
            }

            .menu a {
                padding: 10px;
                margin: 0;
                font-size: 10px;
            }

            .sidebar-promo {
                display: none;
            }

            .sidebar-bottom {
                margin-top: 10px;
            }

            .main {
                padding: 22px 15px;
            }

            .stats {
                grid-template-columns: 1fr;
            }

            .hero {
                padding: 25px;
            }

            .program-banner {
                padding: 20px;
            }

            .topbar {
                gap: 10px;
            }
        }
    </style>
</head>

<body>

<div class="layout">

    <!-- SIDEBAR -->
    <aside class="sidebar">

        <div>
            <div class="brand">FORM<span>LAB</span></div>
            <div class="brand-subtitle">TENNIS CLUB</div>
        </div>

        <div class="menu-title">MENU</div>

        <nav class="menu">
            <a href="{{ route('member.dashboard') }}" class="active">
                <i class="fa-solid fa-house"></i>
                Dashboard
            </a>

            <a href="{{ route('member.classes') }}">
                <i class="fa-solid fa-table-tennis-paddle-ball"></i>
                Classes
            </a>

            <a href="{{ route('member.bookings.index') }}">
                <i class="fa-solid fa-calendar-check"></i>
                My Bookings
            </a>
        </nav>

        <div class="sidebar-promo">
            <h4>PLAY BETTER.<br><span>TRAIN SMARTER.</span></h4>
        </div>

        <div class="sidebar-bottom">
            <form action="{{ url('/logout') }}" method="POST">
                @csrf
                <button class="logout-button" type="submit">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    Logout
                </button>
            </form>
        </div>

    </aside>

    <!-- MAIN CONTENT -->
    <main class="main">

        <!-- TOPBAR -->
        <header class="topbar">

            <div>
                <div class="topbar-title">Dashboard</div>
                <div class="topbar-subtitle">
                    Welcome to your FORMLAB member area
                </div>
            </div>

            <div class="profile">
                <div class="profile-avatar">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>

                <div>
                    <div class="profile-name">{{ $user->name }}</div>
                    <div class="profile-role">FORMLAB Member</div>
                </div>
            </div>

        </header>

        <!-- HERO -->
        <section class="hero">
            <div class="hero-content">

                <div class="hero-label">WELCOME TO FORMLAB</div>

                <h1>
                    Hello, {{ $user->name }}!<br>
                    <span>Ready to Play?</span>
                </h1>

                <p>
                    Your next tennis session starts here.
                    Explore our programs, manage your bookings,
                    and keep improving your game with FORMLAB.
                </p>

                <a href="{{ route('member.classes') }}" class="hero-button">
                    Explore Classes
                    <i class="fa-solid fa-arrow-right"></i>
                </a>

            </div>
        </section>

        <!-- STATISTICS -->
        <div class="section-heading">
            <h3>Your Activity Overview</h3>
        </div>

        <section class="stats">

            <div class="stat-card">
                <div class="stat-top">
                    <div class="stat-label">Total Bookings</div>
                    <div class="stat-icon">
                        <i class="fa-solid fa-calendar-days"></i>
                    </div>
                </div>

                <div class="stat-value">{{ $totalBookings }}</div>
                <div class="stat-note">Total reservations made</div>
            </div>

            <div class="stat-card">
                <div class="stat-top">
                    <div class="stat-label">Confirmed</div>
                    <div class="stat-icon">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                </div>

                <div class="stat-value">{{ $confirmedBookings }}</div>
                <div class="stat-note">Confirmed bookings</div>
            </div>

            <div class="stat-card">
                <div class="stat-top">
                    <div class="stat-label">Paid</div>
                    <div class="stat-icon">
                        <i class="fa-solid fa-wallet"></i>
                    </div>
                </div>

                <div class="stat-value">{{ $paidBookings }}</div>
                <div class="stat-note">Successfully paid bookings</div>
            </div>

        </section>

        <!-- PROGRAM BANNER -->
        <section class="program-banner">

            <div class="program-content">
                <div class="program-label">DISCOVER YOUR POTENTIAL</div>

                <h3>Explore Our Programs</h3>

                <p>
                    Find the right tennis program for your goals
                    and take your game to the next level.
                </p>

                <a href="{{ route('member.classes') }}" class="program-button">
                    View All Classes
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

        </section>

        <!-- UPCOMING AND RECENT -->
        <section class="bottom-grid">

            <!-- UPCOMING SCHEDULE -->
            <div class="panel">

                <div class="panel-heading">
                    <h3>Upcoming Schedule</h3>

                    <a href="{{ route('member.bookings.index') }}">
                        View All
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>

                @forelse ($upcomingBookings as $booking)

                    @foreach ($booking->schedules as $schedule)

                        @if ($schedule->schedule_date->isFuture() || $schedule->schedule_date->isToday())

                            <div class="booking-item">

                                <div class="booking-image"></div>

                                <div class="booking-info">
                                    <h4>{{ $booking->class_name }}</h4>

                                    <p>
                                        <i class="fa-regular fa-calendar"></i>
                                        {{ $schedule->schedule_date->format('d M Y') }}
                                        <br>

                                        <i class="fa-regular fa-clock"></i>
                                        {{ substr($schedule->start_time, 0, 5) }}
                                        -
                                        {{ substr($schedule->end_time, 0, 5) }}
                                        <br>

                                        <i class="fa-solid fa-location-dot"></i>
                                        {{ $schedule->location->name ?? 'Location not specified' }}
                                    </p>
                                </div>

                                <span class="booking-status {{ $booking->status === 'pending' ? 'pending' : ($booking->status === 'cancelled' ? 'cancelled' : '') }}">
                                    {{ ucfirst($booking->status) }}
                                </span>

                            </div>

                        @endif

                    @endforeach

                @empty

                    <div class="empty-state">
                        No upcoming schedule yet.<br>
                        <a href="{{ route('member.classes') }}">
                            Explore available classes
                        </a>
                    </div>

                @endforelse

            </div>

            <!-- RECENT BOOKINGS -->
            <div class="panel">

                <div class="panel-heading">
                    <h3>Recent Bookings</h3>

                    <a href="{{ route('member.bookings.index') }}">
                        View All
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>

                @forelse ($recentBookings as $booking)

                    <div class="booking-item">

                        <div class="booking-image"></div>

                        <div class="booking-info">
                            <h4>{{ $booking->class_name }}</h4>

                            <p>
                                Booking:
                                {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}
                                <br>

                                Total:
                                Rp{{ number_format($booking->total_price, 0, ',', '.') }}
                                <br>

                                Payment:
                                {{ ucfirst($booking->payment_status) }}
                            </p>
                        </div>

                        <span class="booking-status {{ $booking->payment_status === 'paid' ? '' : 'pending' }}">
                            {{ ucfirst($booking->payment_status) }}
                        </span>

                    </div>

                @empty

                    <div class="empty-state">
                        No booking history yet.<br>
                        <a href="{{ route('member.classes') }}">
                            Start your first booking
                        </a>
                    </div>

                @endforelse

            </div>

        </section>

    </main>

</div>

</body>
</html>
