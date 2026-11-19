
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Schedule | FORMLAB</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    <style>
        .form-card {
            max-width: 850px;
            background: white;
            padding: 30px;
            border-radius: 14px;
            margin-top: 25px;
        }

        .form-group {
            margin-bottom: 22px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 9px;
        }

        .form-control {
            width: 100%;
            padding: 13px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            box-sizing: border-box;
        }

        .form-control:focus {
            outline: none;
            border-color: #174d38;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .btn-save {
            background: #174d38;
            color: white;
            border: none;
            padding: 13px 22px;
            border-radius: 8px;
            cursor: pointer;
        }

        .btn-cancel {
            background: #eee;
            color: #333;
            padding: 13px 22px;
            border-radius: 8px;
            text-decoration: none;
        }

        .error-box {
            background: #fde8e7;
            color: #b42318;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .info-box {
            background: #e8f0ec;
            color: #174d38;
            padding: 13px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
        }

        @media (max-width: 700px) {
            .form-row {
                grid-template-columns: 1fr;
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
        <a href="{{ route('admin.schedules.index') }}" class="active">Schedules</a>
        <a href="/admin/payments">Payments</a>
        <a href="/admin/settings">Settings</a>
        <a href="/">Back to Website</a>
    </aside>

    <main class="main-content">

        <div class="topbar">
            <h1>Add Training Schedule</h1>
            <div class="admin-profile">Administrator</div>
        </div>

        <div class="form-card">

            <h2>Create New Schedule</h2>
            <p style="color:#777; margin:8px 0 25px;">
                Masukkan informasi jadwal latihan FORMLAB.
            </p>

            <div class="info-box">
                Setiap pertemuan berlangsung selama 2 jam.
                Jam selesai akan dihitung otomatis berdasarkan jam mulai.
            </div>

            @if ($errors->any())
                <div class="error-box">
                    <strong>Periksa kembali data berikut:</strong>
                    <ul style="padding-left:20px; margin-top:8px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.schedules.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>Training Class</label>
                    <select name="class_id" class="form-control" required>
                        <option value="">Select Training Class</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}"
                                {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Training Location</label>
                    <select name="location_id" class="form-control" required>
                        <option value="">Select Location</option>
                        @foreach ($locations as $location)
                            <option value="{{ $location->id }}"
                                {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                {{ $location->name }} — {{ $location->address }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Schedule Date</label>
                        <input
                            type="date"
                            name="schedule_date"
                            class="form-control"
                            min="{{ date('Y-m-d') }}"
                            value="{{ old('schedule_date') }}"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label>Start Time</label>
                        <input
                            type="time"
                            name="start_time"
                            id="start_time"
                            class="form-control"
                            value="{{ old('start_time') }}"
                            required
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label>Estimated End Time</label>
                    <input
                        type="text"
                        id="end_time"
                        class="form-control"
                        placeholder="Automatically calculated"
                        readonly
                        style="background:#f5f5f5;"
                    >
                    <small style="color:#777;">
                        Otomatis 2 jam setelah jam mulai.
                    </small>
                </div>

                <div class="form-group">
                    <label>Maximum Participants</label>
                    <input
                        type="number"
                        name="capacity"
                        id="capacity"
                        class="form-control"
                        min="1"
                        max="100"
                        value="{{ old('capacity', 6) }}"
                        required
                    >
                    <small style="color:#777;">
                        Khusus kelas Regular, kapasitas maksimal 6 peserta.
                    </small>
                </div>

                <div class="form-group">
                    <label>Training Notes (Optional)</label>
                    <textarea
                        name="note"
                        class="form-control"
                        rows="4"
                        placeholder="Contoh: Peserta diharapkan datang 15 menit lebih awal."
                    >{{ old('note') }}</textarea>
                </div>

                <div style="display:flex; gap:12px; flex-wrap:wrap;">
                    <button type="submit" class="btn-save">
                        Save Schedule
                    </button>

                    <a href="{{ route('admin.schedules.index') }}" class="btn-cancel">
                        Cancel
                    </a>
                </div>

            </form>
        </div>

    </main>
</div>

<script>
    const startInput = document.getElementById('start_time');
    const endInput = document.getElementById('end_time');

    function calculateEndTime() {
        if (!startInput.value) {
            endInput.value = '';
            return;
        }

        const [hours, minutes] = startInput.value.split(':').map(Number);

        const startMinutes = hours * 60 + minutes;
        const endMinutes = (startMinutes + 120) % (24 * 60);

        const endHours = Math.floor(endMinutes / 60);
        const endMins = endMinutes % 60;

        endInput.value =
            String(endHours).padStart(2, '0') + ':' +
            String(endMins).padStart(2, '0');
    }

    startInput.addEventListener('input', calculateEndTime);
    calculateEndTime();
</script>

</body>
</html>

