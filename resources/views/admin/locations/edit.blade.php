
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Location | FORMLAB</title>
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
        <a href="/admin/payments">Payments</a>
        <a href="/admin/settings">Settings</a>
        <a href="/">Back to Website</a>
    </aside>

    <main class="main-content">

        <div class="topbar">
            <h1>Edit Training Location</h1>
            <div class="admin-profile">Administrator</div>
        </div>

        <div style="max-width: 750px; background: white; padding: 30px; border-radius: 14px;">

            <h2 style="margin-bottom: 8px;">Update Location</h2>
            <p style="color: #777; margin-bottom: 25px;">
                Perbarui informasi lokasi latihan FORMLAB.
            </p>

            @if ($errors->any())
                <div style="background: #ffe5e5; color: #b42318; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                    <strong>Periksa kembali data berikut:</strong>
                    <ul style="padding-left: 20px; margin-top: 8px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.locations.update', $location->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600;">
                        Location Name
                    </label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $location->name) }}"
                        required
                        style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px;"
                    >
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600;">
                        Full Address
                    </label>
                    <textarea
                        name="address"
                        rows="4"
                        required
                        style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px;"
                    >{{ old('address', $location->address) }}</textarea>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600;">
                        Google Maps Link
                    </label>
                    <input
                        type="url"
                        name="google_maps_link"
                        value="{{ old('google_maps_link', $location->google_maps_link) }}"
                        required
                        style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px;"
                    >
                </div>

                <div style="margin-bottom: 25px;">
                    <label style="display: flex; align-items: center; gap: 10px;">
                        <input
                            type="checkbox"
                            name="is_active"
                            value="1"
                            {{ old('is_active', $location->is_active) ? 'checked' : '' }}
                        >
                        <span>Location is active</span>
                    </label>
                </div>

                <div style="display: flex; gap: 12px;">
                    <button
                        type="submit"
                        style="background: #174d38; color: white; padding: 12px 22px; border: none; border-radius: 8px; cursor: pointer;"
                    >
                        Save Changes
                    </button>

                    <a
                        href="{{ route('admin.locations.index') }}"
                        style="background: #eee; color: #333; padding: 12px 22px; border-radius: 8px; text-decoration: none;"
                    >
                        Cancel
                    </a>
                </div>

            </form>
        </div>

    </main>
</div>

</body>
</html>
