
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Training Schedules | FORMLAB</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    <style>
        .schedule-card {
            background: white;
            padding: 25px;
            border-radius: 14px;
            margin-top: 25px;
        }

        .schedule-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .schedule-table th,
        .schedule-table td {
            padding: 15px 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .schedule-table th {
            color: #777;
            font-size: 12px;
            text-transform: uppercase;
        }

        .schedule-table td {
            font-size: 14px;
        }

        .btn-add {
            background: #174d38;
            color: white;
            padding: 12px 18px;
            border-radius: 8px;
            text-decoration: none;
        }

        .btn-edit {
            background: #e8f0ec;
            color: #174d38;
            padding: 7px 12px;
            border-radius: 6px;
            text-decoration: none;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
            padding: 7px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .status-active {
            color: #16834a;
            background: #e4f5eb;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        .status-inactive {
            color: #b42318;
            background: #fde8e7;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        .alert-success {
            background: #e4f5eb;
            color: #16834a;
            padding: 14px;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .alert-error {
            background: #fde8e7;
            color: #b42318;
            padding: 14px;
            border-radius: 8px;
            margin-bottom: 15px;
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
        <a href="{{ route('admin.schedules.index') }}" class="active">Schedules</a>
        <a href="/admin/payments">Payments</a>
        <a href="/admin/settings">Settings</a>
        <a href="{{ route('admin.notes.index') }}">Notes</a>

        <a href="/">Back to Website</a>
    </aside>

    <main class="main-content">

        <div class="topbar">
            <h1>Training Schedules</h1>
            <div class="admin-profile">Administrator</div>
        </div>

        <div class="schedule-card">

            <div style="display:flex; justify-content:space-between; align-items:center; gap:15px; flex-wrap:wrap;">
                <div>
                    <h2>Class Schedule Management</h2>
                    <p style="color:#777; margin-top:7px;">
                        Kelola jadwal latihan FORMLAB.
                    </p>
                </div>

                <a href="{{ route('admin.schedules.create') }}" class="btn-add">
                    + Add Schedule
                </a>
            </div>

                        <form action="{{ route('admin.schedules.index') }}"
                            method="GET"
                            class="booking-search"
                            style="margin-top:25px;">

                            <div class="search-box">
                                <input
                                    type="text"
                                    name="search"
                                    placeholder="Cari nama kelas, lokasi, atau tanggal..."
                                    value="{{ request('search') }}"
                                >
                            </div>

                            <button type="submit">Cari</button>

                            <a href="{{ route('admin.schedules.index') }}">Reset</a>

                        </form>

                        <p class="booking-total">
                            Menampilkan {{ $schedules->count() }}
                            dari {{ $schedules->total() }} jadwal
                        </p>

            @if(session('success'))
                <div class="alert-success" style="margin-top:20px;">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert-error" style="margin-top:20px;">
                    {{ session('error') }}
                </div>
            @endif

            <div class="table-wrapper">
                <table class="schedule-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Date</th>
                            <th>Class</th>
                            <th>Time</th>
                            <th>Location</th>
                            <th>Capacity</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($schedules as $schedule)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    {{ $schedule->schedule_date->format('d M Y') }}
                                </td>

                                <td>
                                    {{ $schedule->tennisClass->name ?? '-' }}
                                </td>

                                <td>
                                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}
                                    -
                                    {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                </td>

                                <td>
                                    {{ $schedule->location->name ?? '-' }}
                                </td>

                                <td>
                                    {{ $schedule->bookings->sum('participants') }}
                                    /
                                    {{ $schedule->capacity }} peserta
                                </td>

                                <td>
                                    @if($schedule->is_available)
                                        <span class="status-active">Available</span>
                                    @else
                                        <span class="status-inactive">Unavailable</span>
                                    @endif
                                </td>

                                <td>
                                    <a href="{{ route('admin.schedules.edit', $schedule->id) }}"
                                       class="btn-edit">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.schedules.destroy', $schedule->id) }}"
                                          method="POST"
                                          style="display:inline;"
                                          onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn-delete">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="8" style="text-align:center; padding:35px; color:#888;">
                                    Belum ada jadwal latihan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
                        <div class="booking-pagination">

                            <div>
                                Halaman {{ $schedules->currentPage() }}
                                dari {{ $schedules->lastPage() }}
                            </div>

                            <div>
                                {{ $schedules->links('pagination::simple-tailwind') }}
                            </div>

                        </div>
        </div>

    </main>
</div>

</body>
</html>
