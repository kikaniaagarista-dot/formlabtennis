
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Members | FORMLAB Admin</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
<div class="admin-container">

    <aside class="sidebar">
        <h2>FORMLAB</h2>
        <p>ADMIN PANEL</p>

        <a href="/admin">Dashboard</a>
        <a href="/admin/bookings">Bookings</a>
        <a href="/admin/members" class="active">Members</a>
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
            <h1>Members Management</h1>
            <div class="admin-profile">Administrator</div>
        </div>

        <h2>All Members</h2>
        <p>View and monitor registered FORMLAB members.</p>
        
            <form action="/admin/members" method="GET" class="booking-search">

                <div class="search-box">
                    <input
                        type="text"
                        name="search"
                        placeholder="Cari nama atau email member..."
                        value="{{ request('search') }}"
                    >
                </div>

                <button type="submit">Cari</button>

                <a href="/admin/members">Reset</a>

            </form>

            <p class="booking-total">
                Menampilkan {{ $members->count() }} dari {{ $members->total() }} member
            </p>

        <div class="booking-table">
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Member Name</th>
                        <th>Email</th>
                        <th>Joined Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($members as $member)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $member->name }}</td>
                            <td>{{ $member->email }}</td>
                            <td>{{ $member->created_at->format('d M Y') }}</td>
                            <td>
                                <form action="/admin/members/{{ $member->id }}"
                                    method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this member?');">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center;">
                                No members registered yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="booking-pagination">

            <div>
                Halaman {{ $members->currentPage() }}
                dari {{ $members->lastPage() }}
            </div>

            <div>
                {{ $members->links('pagination::simple-tailwind') }}
            </div>

        </div>

    </main>

</div>
</body>
</html>
