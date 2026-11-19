
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coaches | FORMLAB Admin</title>
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
        <a href="/admin/coaches" class="active">Coaches</a>

        <a href="{{ route('admin.locations.index') }}">Locations</a>
        <a href="{{ route('admin.schedules.index') }}">Schedules</a>

        <a href="/admin/payments">Payments</a>
        <a href="/admin/settings">Settings</a>
        <a href="{{ route('admin.notes.index') }}">Notes</a>

        <a href="/">Back to Website</a>
    </aside>

    <main class="main-content">
        <div class="topbar">
            <h1>Coaches Management</h1>
            <div class="admin-profile">Administrator</div>
        </div>

        <h2>All Coaches</h2>
        <p>Manage and monitor FORMLAB tennis coaches.</p>

        <form action="/admin/coaches" method="GET" class="booking-search">

            <div class="search-box">
                <input
                    type="text"
                    name="search"
                    placeholder="Cari nama atau spesialisasi coach..."
                    value="{{ request('search') }}"
                >
            </div>

            <button type="submit">Cari</button>

            <a href="/admin/coaches">Reset</a>

        </form>

        <p class="booking-total">
            Menampilkan {{ $coaches->count() }} dari {{ $coaches->total() }} coach
        </p>

        @if (session('success'))
            <p style="color: green;">{{ session('success') }}</p>
        @endif

        <a href="{{ route('admin.coaches.create') }}" class="coach-add-btn">
            <span>+</span> Add New Coach
        </a>

        <div class="booking-table">
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Photo</th>
                        <th>Coach Name</th>
                        <th>Specialization</th>
                        <th>Biography</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($coaches as $coach)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if ($coach->photo)
                                    <img src="{{ asset('images/coaches/' . $coach->photo) }}"
                                         width="55" height="55"
                                         style="object-fit: cover; border-radius: 8px;">
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $coach->name }}</td>
                            <td>{{ $coach->specialization ?? '-' }}</td>
                            <td>{{ $coach->bio ?? '-' }}</td>
                            <td>{{ $coach->is_active ? 'Active' : 'Inactive' }}</td>
                            <td>
                                <a href="{{ route('admin.coaches.edit', $coach->id) }}" class="coach-edit-btn">
                                    Edit
                                </a>

                                <form action="{{ route('admin.coaches.destroy', $coach->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Are you sure you want to delete this coach?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center;">
                                No coaches available yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="booking-pagination">

        <div>
            Halaman {{ $coaches->currentPage() }}
            dari {{ $coaches->lastPage() }}
        </div>

        <div>
            {{ $coaches->links('pagination::simple-tailwind') }}
        </div>

    </div>
    </main>

</div>
</body>
</html>
