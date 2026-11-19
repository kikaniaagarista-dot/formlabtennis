
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Catatan | FORMLAB</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    <style>
        .note-page {
            padding: 35px;
            color: #f5f5f5;
            max-width: 850px;
        }

        .note-page h1 {
            margin-bottom: 8px;
        }

        .subtitle {
            color: #999;
            margin-bottom: 30px;
        }

        .note-form {
            background: #191919;
            border: 1px solid #303030;
            border-radius: 15px;
            padding: 28px;
        }

        .form-group {
            margin-bottom: 22px;
        }

        .form-group label {
            display: block;
            margin-bottom: 9px;
            font-weight: 600;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 13px 14px;
            border-radius: 8px;
            border: 1px solid #444;
            background: #242424;
            color: white;
            font: inherit;
            box-sizing: border-box;
        }

        .form-group textarea {
            min-height: 180px;
            resize: vertical;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #b6f23a;
        }

        .error {
            color: #ff7777;
            font-size: 13px;
            margin-top: 7px;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 28px;
        }

        .btn-save,
        .btn-back {
            padding: 12px 20px;
            border-radius: 8px;
            border: none;
            text-decoration: none;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-save {
            background: #b6f23a;
            color: #111;
        }

        .btn-back {
            background: #333;
            color: white;
        }

        @media(max-width: 700px) {
            .note-page {
                padding: 22px 15px;
            }
        }
    </style>
</head>

<body>

<div class="dashboard-container">

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
        <a href="/admin/settings">Settings</a>
        <a href="{{ route('admin.notes.index') }}" class="active">Notes</a>
        <a href="/">Back to Website</a>
    </aside>

    <main class="main-content">
        <div class="note-page">

            <h1>Edit Catatan</h1>
            <p class="subtitle">Perbarui informasi catatan FORMLAB.</p>

            <form action="{{ route('admin.notes.update', $note->id) }}"
                  method="POST"
                  class="note-form">

                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Judul Catatan</label>
                    <input type="text"
                           name="title"
                           value="{{ old('title', $note->title) }}"
                           required>

                    @error('title')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Isi Catatan</label>
                    <textarea name="content" required>{{ old('content', $note->content) }}</textarea>

                    @error('content')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Warna Catatan</label>
                    <select name="color" required>
                        <option value="yellow" {{ old('color', $note->color) == 'yellow' ? 'selected' : '' }}>Kuning</option>
                        <option value="pink" {{ old('color', $note->color) == 'pink' ? 'selected' : '' }}>Pink</option>
                        <option value="blue" {{ old('color', $note->color) == 'blue' ? 'selected' : '' }}>Biru</option>
                        <option value="green" {{ old('color', $note->color) == 'green' ? 'selected' : '' }}>Hijau</option>
                        <option value="purple" {{ old('color', $note->color) == 'purple' ? 'selected' : '' }}>Ungu</option>
                    </select>

                    @error('color')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-save">
                        Simpan Perubahan
                    </button>

                    <a href="{{ route('admin.notes.index') }}" class="btn-back">
                        Kembali
                    </a>
                </div>

            </form>

        </div>
    </main>

</div>

</body>
</html>

