<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Location | FORMLAB</title>
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
        <a href="/admin/locations" class="active">Locations</a>
        <a href="/admin/payments">Payments</a>
        <a href="/admin/settings">Settings</a>
        <a href="/">Back to Website</a>
    </aside>

    <main class="main-content">

        <div class="topbar">
            <h1>Add Training Location</h1>
            <div class="admin-profile">Administrator</div>
        </div>

        <div style="max-width:800px;">

            <div style="margin-bottom:25px;">
                <h2>New Location</h2>
                <p style="color:#888; margin-top:8px;">
                    Add a location for FORMLAB training sessions.
                </p>
            </div>

            @if ($errors->any())
                <div style="background:#472323; color:#ff8888; padding:15px; border-radius:8px; margin-bottom:20px;">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="stat-card">

                <form action="{{ route('admin.locations.store') }}" method="POST">
                    @csrf

                    <div style="margin-bottom:22px;">
                        <label for="name" style="display:block; margin-bottom:9px; font-weight:bold;">
                            Location Name
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Example: Samarinda Tennis Court"
                            required
                            style="width:100%; padding:13px; border:1px solid #444; border-radius:7px; background:#222; color:white;"
                        >
                    </div>

                    <div style="margin-bottom:22px;">
                        <label for="address" style="display:block; margin-bottom:9px; font-weight:bold;">
                            Full Address
                        </label>

                        <textarea
                            id="address"
                            name="address"
                            rows="4"
                            placeholder="Enter complete location address"
                            required
                            style="width:100%; padding:13px; border:1px solid #444; border-radius:7px; background:#222; color:white; resize:vertical;"
                        >{{ old('address') }}</textarea>
                    </div>

                    <div style="margin-bottom:22px;">
                        <label for="google_maps_link" style="display:block; margin-bottom:9px; font-weight:bold;">
                            Google Maps Link
                        </label>

                        <input
                            type="url"
                            id="google_maps_link"
                            name="google_maps_link"
                            value="{{ old('google_maps_link') }}"
                            placeholder="https://maps.google.com/..."
                            required
                            style="width:100%; padding:13px; border:1px solid #444; border-radius:7px; background:#222; color:white;"
                        >

                        <small style="display:block; color:#888; margin-top:8px;">
                            Paste the Google Maps link of this location.
                        </small>
                    </div>

                    <div style="margin-bottom:25px;">
                        <label style="display:flex; align-items:center; gap:10px;">
                            <input type="checkbox" name="is_active" value="1" checked>
                            Active Location
                        </label>
                    </div>

                    <div style="display:flex; justify-content:flex-end; gap:12px;">
                        <a href="{{ route('admin.locations.index') }}"
                           style="padding:12px 18px; background:#333; color:white; border-radius:7px; text-decoration:none;">
                            Cancel
                        </a>

                        <button type="submit"
                                style="padding:12px 20px; background:#b6ff00; color:#111; border:none; border-radius:7px; font-weight:bold; cursor:pointer;">
                            Save Location
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </main>
</div>

</body>
</html>
