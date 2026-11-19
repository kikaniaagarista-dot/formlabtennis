
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings | FORMLAB Admin</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">


    <style>
        :root {
            --lime: #b6ff00;
            --dark: #111311;
            --card: #1b1e1b;
            --border: #303630;
            --muted: #929a91;
        }

        .main-content {
            padding: 35px;
            background: var(--dark);
            min-height: 100vh;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 35px;
        }

        .topbar h1 {
            font-size: 30px;
            font-weight: 700;
            color: #fff;
            letter-spacing: -1px;
        }

        .admin-profile {
            background: #252a25;
            border: 1px solid var(--border);
            padding: 11px 18px;
            border-radius: 30px;
            color: var(--lime);
            font-size: 13px;
        }

        .main-content > h2 {
            font-size: 23px;
            color: white;
            margin-bottom: 8px;
        }

        .main-content > p {
            color: var(--muted);
            margin-bottom: 28px;
        }

        .booking-table {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 28px;
            margin-top: 22px;
            box-shadow: 0 8px 25px rgba(0,0,0,.15);
        }

        .booking-table h3 {
            color: white;
            font-size: 18px;
            margin: 0 0 12px;
        }

        .booking-table > p {
            color: var(--muted);
            font-size: 13px;
            margin-bottom: 22px;
        }

        .settings-info {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .settings-info p {
            background: #242824;
            border: 1px solid #343a34;
            border-radius: 10px;
            padding: 15px;
            margin: 0;
            color: #c5cbc3;
            font-size: 13px;
        }

        .settings-info strong {
            display: block;
            color: var(--lime);
            margin-bottom: 6px;
        }

        .settings-form {
            max-width: 650px;
            margin-top: 25px;
        }

        .settings-form label {
            display: block;
            color: #e5e9e3;
            font-size: 13px;
            font-weight: 600;
            margin: 22px 0 10px;
        }

        .settings-form input[type="text"] {
            width: 100%;
            padding: 14px 16px;
            border-radius: 9px;
            border: 1px solid #3b433b;
            background: #121512;
            color: white;
            outline: none;
            transition: .2s;
        }

        .settings-form input[type="text"]:focus {
            border-color: var(--lime);
            box-shadow: 0 0 0 3px rgba(182,255,0,.08);
        }

        .settings-form input[type="file"] {
            width: 100%;
            padding: 14px;
            background: #121512;
            color: #bfc6bc;
            border: 1px dashed #515b50;
            border-radius: 10px;
            cursor: pointer;
        }

        .settings-form input[type="file"]::file-selector-button {
            background: var(--lime);
            color: #111;
            border: none;
            border-radius: 6px;
            padding: 9px 14px;
            margin-right: 12px;
            font-weight: 700;
            cursor: pointer;
        }

        .qris-preview {
            display: inline-flex;
            padding: 15px;
            background: #242824;
            border: 1px solid #3b433b;
            border-radius: 12px;
            margin-top: 5px;
        }

        .qris-preview img {
            display: block;
            width: 250px;
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            background: white;
        }

        .qris-preview p {
            color: var(--muted);
            font-size: 13px;
        }

        .settings-form label.status-label {
            display: flex;
            align-items: center;
            gap: 12px;
            background: #242824;
            border: 1px solid #3b433b;
            border-radius: 10px;
            padding: 16px;
            cursor: pointer;
        }

        .settings-form input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--lime);
            cursor: pointer;
        }

        .save-button {
            margin-top: 25px;
            padding: 14px 25px;
            border: none;
            border-radius: 9px;
            background: var(--lime);
            color: #101210;
            font-size: 13px;
            font-weight: 800;
            cursor: pointer;
            transition: .2s;
        }

        .save-button:hover {
            background: #d0ff65;
            transform: translateY(-2px);
        }

        .success-message {
            padding: 15px 18px;
            margin: 18px 0;
            border: 1px solid #365b29;
            background: #192719;
            color: #b6ff83;
            border-radius: 10px;
        }

        .error-message {
            color: #ff8585;
            background: #301b1b;
            border: 1px solid #633333;
            padding: 14px 18px;
            border-radius: 10px;
            margin: 15px 0;
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 20px;
            }

            .settings-info {
                grid-template-columns: 1fr;
            }

            .booking-table {
                padding: 20px;
            }

            .topbar h1 {
                font-size: 24px;
            }
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
        <a href="{{ route('admin.schedules.index') }}">Schedules</a>

        <a href="/admin/payments">Payments</a>
        <a href="/admin/settings" class="active">Settings</a>
        <a href="{{ route('admin.notes.index') }}">Notes</a>

        <a href="/">Back to Website</a>
    </aside>

    <main class="main-content">

        <div class="topbar">
            <h1>Settings</h1>
            <div class="admin-profile">Administrator</div>
        </div>

        <h2>Website Settings</h2>
        <p>Manage your FORMLAB website information.</p>

        @if (session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="error-message">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="booking-table">
            <h3>General Information</h3>

            <div class="settings-info">
                <p><strong>Website Name:</strong> FORMLAB Tennis Club</p>
                <p><strong>Website:</strong> FORMLAB Official Website</p>
                <p><strong>Platform:</strong> Laravel</p>
                <p><strong>Admin Panel:</strong> Active</p>
            </div>
        </div>

        <div class="booking-table">
            <h3>Payment Settings - QRIS</h3>
            <p>Manage the QRIS payment method for FORMLAB members.</p>

            <form
                action="{{ route('admin.settings.update') }}"
                method="POST"
                enctype="multipart/form-data"
                class="settings-form"
            >
                @csrf
                @method('PUT')

                <label for="payment_name">Payment Name</label>
                <input
                    type="text"
                    name="payment_name"
                    id="payment_name"
                    value="{{ old('payment_name', $setting->payment_name) }}"
                    required
                >

                <label>Current QRIS Image</label>

                <div class="qris-preview">
                    @if ($setting->qris_image)
                        <img
                            src="{{ asset('storage/' . $setting->qris_image) }}"
                            alt="Current QRIS"
                        >
                    @else
                        <p>No QRIS image uploaded yet.</p>
                    @endif
                </div>

                <label for="qris_image">Upload / Change QRIS Image</label>
                <input
                    type="file"
                    name="qris_image"
                    id="qris_image"
                    accept="image/png,image/jpeg,image/webp"
                >

                <label class="status-label">
                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        {{ $setting->is_active ? 'checked' : '' }}
                    >
                    Activate QRIS Payment
                </label>

                <button type="submit" class="save-button">
                    Save Payment Settings
                </button>
            </form>
        </div>

        <div class="booking-table">
            <h3>System Information</h3>

            <div class="settings-info">
                <p><strong>Application:</strong> FORMLAB</p>
                <p><strong>Environment:</strong> Local Development</p>
                <p><strong>Database:</strong> MySQL</p>
                <p><strong>Status:</strong> Running</p>
            </div>
        </div>

    </main>
</div>
</body>
</html>
