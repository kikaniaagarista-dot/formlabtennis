<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | FORMLAB</title>

    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>

<div class="admin-container">

    <aside class="sidebar">
        <h2>FORMLAB</h2>
        <p>ADMIN PANEL</p>

        <a href="/admin" class="active">Dashboard</a>
        <a href="/admin/bookings">Bookings</a>
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
            <h1>Dashboard</h1>
            <div class="admin-profile">
                Administrator
            </div>
        </div>

        <h2>Welcome Back, Admin!</h2>
        <p style="color: #777; margin-top: 8px; margin-bottom: 30px;">
            Here's what's happening with FORMLAB today.
        </p>

        <div class="stats-grid">

            <div class="stat-card">
                <p>Total Members</p>
                <h2>{{ $totalMembers }}</h2>
            </div>

            <div class="stat-card">
                <p>Total Bookings</p>
                <h2>{{ $totalBookings }}</h2>
            </div>

            <div class="stat-card">
                <p>Active Classes</p>
                <h2>{{ $activeClasses }}</h2>
            </div>

            <div class="stat-card">
                <p>Total Revenue</p>
                <h2>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h2>
            </div>
            <div class="stat-card">
                <p>Pending Payment</p>
                <h2>Rp {{ number_format($pendingPayment, 0, ',', '.') }}</h2>
            </div>
        </div>

    </main>

</div>

</body>
</html>