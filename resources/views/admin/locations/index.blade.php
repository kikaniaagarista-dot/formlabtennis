
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Training Locations | FORMLAB</title>
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

        <a href="{{ route('admin.locations.index') }}" class="active">Locations</a>
        <a href="{{ route('admin.schedules.index') }}">Schedules</a>

        <a href="/admin/payments">Payments</a>
        <a href="/admin/settings">Settings</a>
        <a href="{{ route('admin.notes.index') }}">Notes</a>

        <a href="/">Back to Website</a>
    </aside>
    
    <main class="main-content">

        <div class="topbar">
            <h1>Training Locations</h1>
            <div class="admin-profile">Administrator</div>
        </div>

        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:25px;">
            <div>
                <h2>Location Management</h2>
                <p style="color:#888; margin-top:8px;">
                    Manage your tennis training locations.
                </p>
            </div>

            <a href="{{ route('admin.locations.create') }}"
               style="background:#b6ff00; color:#111; padding:12px 18px; border-radius:7px; text-decoration:none; font-weight:bold;">
                + Add Location
            </a>
        </div>

                <form action="{{ route('admin.locations.index') }}" method="GET" class="booking-search">

                    <div class="search-box">
                        <input
                            type="text"
                            name="search"
                            placeholder="Cari nama atau alamat lokasi..."
                            value="{{ request('search') }}"
                        >
                    </div>

                    <button type="submit">Cari</button>

                    <a href="{{ route('admin.locations.index') }}">Reset</a>

                </form>

                <p class="booking-total">
                    Menampilkan {{ $locations->count() }} dari {{ $locations->total() }} lokasi
                </p>

        @if(session('success'))
            <p style="background:#20320a; color:#b6ff00; padding:14px; border-radius:8px; margin-bottom:20px;">
                {{ session('success') }}
            </p>
        @endif

        @if(session('error'))
            <p style="background:#472323; color:#ff8888; padding:14px; border-radius:8px; margin-bottom:20px;">
                {{ session('error') }}
            </p>
        @endif

        <div class="stat-card" style="padding:0; overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; min-width:800px;">
                <thead>
                    <tr style="background:#222; color:#aaa;">
                        <th style="padding:16px; text-align:left;">No.</th>
                        <th style="padding:16px; text-align:left;">Location</th>
                        <th style="padding:16px; text-align:left;">Address</th>
                        <th style="padding:16px; text-align:left;">Google Maps</th>
                        <th style="padding:16px; text-align:left;">Status</th>
                        <th style="padding:16px; text-align:left;">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($locations as $location)
                        <tr style="border-top:1px solid #333;">
                            <td style="padding:16px;">{{ $loop->iteration }}</td>

                            <td style="padding:16px; font-weight:bold;">
                                {{ $location->name }}
                            </td>

                            <td style="padding:16px;">
                                {{ $location->address }}
                            </td>

                            <td style="padding:16px;">
                                <a href="{{ $location->google_maps_link }}"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   style="color:#b6ff00;">
                                    Open Maps ↗
                                </a>
                            </td>

                            <td style="padding:16px;">
                                {{ $location->is_active ? 'Active' : 'Inactive' }}
                            </td>

                            <td style="padding:16px;">
                                <a href="{{ route('admin.locations.edit', $location->id) }}"
                                   style="color:#b6ff00; margin-right:8px;">
                                    Edit
                                </a>

                                <form action="{{ route('admin.locations.destroy', $location->id) }}"
                                      method="POST"
                                      style="display: inline;"
                                      onsubmit="return confirm('Yakin ingin menghapus lokasi ini?')">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                        style="background: #dc3545; color: white; padding: 8px 14px; border: none; border-radius: 6px; cursor: pointer;">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding:30px; text-align:center; color:#888;">
                                No locations added yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
            <div class="booking-pagination">

                <div>
                    Halaman {{ $locations->currentPage() }}
                    dari {{ $locations->lastPage() }}
                </div>

                <div>
                    {{ $locations->links('pagination::simple-tailwind') }}
                </div>

            </div>

    </main>
</div>

</body>
</html>
