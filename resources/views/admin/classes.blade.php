
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classes | FORMLAB Admin</title>
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
        <a href="/admin/classes" class="active">Classes</a>
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
            <h1>Classes Management</h1>
            <div class="admin-profile">Administrator</div>
        </div>

        <h2>Available Classes</h2>
        <p>Manage and monitor FORMLAB tennis classes.</p>

            <form action="/admin/classes" method="GET" class="booking-search">

                <div class="search-box">
                    <input
                        type="text"
                        name="search"
                        placeholder="Cari nama kelas..."
                        value="{{ request('search') }}"
                    >
                </div>

                <select name="type">
                    <option value="">Semua Jenis Kelas</option>
                    <option value="regular" {{ request('type') == 'regular' ? 'selected' : '' }}>
                        Regular
                    </option>
                    <option value="private" {{ request('type') == 'private' ? 'selected' : '' }}>
                        Private
                    </option>
                </select>

                <button type="submit">Cari</button>

                <a href="/admin/classes">Reset</a>

            </form>

            <p class="booking-total">
                Menampilkan {{ $classes->count() }} dari {{ $classes->total() }} kelas
            </p>

        @if (session('success'))
            <p style="color: green;">{{ session('success') }}</p>
        @endif

        <a href="{{ route('admin.classes.create') }}"
           style="display:inline-block; padding:12px 20px; background:#b6f36b; color:#111; text-decoration:none; border-radius:8px; margin:20px 0;">
            + Add New Class
        </a>

        <div class="booking-table">
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Class Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Capacity</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($classes as $class)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $class->name }}</td>
                            <td>{{ $class->description ?? '-' }}</td>
                            <td>Rp {{ number_format($class->price, 0, ',', '.') }}</td>
                            <td>{{ $class->capacity }} People</td>
                            <td>{{ $class->is_active ? 'Active' : 'Inactive' }}</td>
                            <td>
                                <a href="{{ route('admin.classes.edit', $class->id) }}">
                                    Edit
                                </a>

                                <form action="{{ route('admin.classes.destroy', $class->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Delete this class?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center;">
                                No classes available yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="booking-pagination">

            <div>
                Halaman {{ $classes->currentPage() }}
                dari {{ $classes->lastPage() }}
            </div>

            <div>
                {{ $classes->links('pagination::simple-tailwind') }}
            </div>

        </div>
        
    </main>

</div>
</body>
</html>
