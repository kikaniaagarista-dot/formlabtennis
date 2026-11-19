
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Notes | FORMLAB</title>

    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    <style>
        .notes-page {
            padding: 35px;
            color: #f5f5f5;
        }

        .notes-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            gap: 20px;
        }

        .notes-header h1 {
            font-size: 28px;
            margin-bottom: 7px;
        }

        .notes-header p {
            color: #999;
        }

        .add-note {
            background: #b6f23a;
            color: #111;
            padding: 12px 20px;
            border-radius: 9px;
            text-decoration: none;
            font-weight: 700;
        }

        .notes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 22px;
        }

        .sticky-note {
            min-height: 220px;
            padding: 22px;
            border-radius: 14px;
            color: #222;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 0 8px 22px rgba(0,0,0,.2);
        }

        .sticky-note h3 {
            font-size: 19px;
            margin-bottom: 14px;
        }

        .sticky-note p {
            white-space: pre-wrap;
            overflow-wrap: anywhere;
            line-height: 1.7;
            font-size: 14px;
        }

        .yellow { background: #fff0a8; }
        .pink { background: #ffc8d5; }
        .blue { background: #c5e5ff; }
        .green { background: #d5f5bd; }
        .purple { background: #e3d1ff; }

        .note-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        .note-actions a,
        .note-actions button {
            border: none;
            padding: 8px 12px;
            border-radius: 7px;
            cursor: pointer;
            text-decoration: none;
            font-size: 12px;
        }

        .edit-btn {
            background: #222;
            color: white;
        }

        .delete-btn {
            background: #d83b3b;
            color: white;
        }

        .empty-notes {
            padding: 50px 20px;
            text-align: center;
            border: 1px dashed #444;
            border-radius: 14px;
            color: #999;
            grid-column: 1 / -1;
        }

        .success-message {
            background: #1e3b23;
            color: #b6f23a;
            padding: 13px 16px;
            border-radius: 9px;
            margin-bottom: 22px;
        }

        @media(max-width: 700px) {
            .notes-page {
                padding: 22px 15px;
            }

            .notes-header {
                align-items: flex-start;
                flex-direction: column;
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
        <div class="notes-page">

            <div class="notes-header">
                <div>
                    <h1>Dashboard Notes</h1>
                    <p>Simpan informasi dan pengingat penting FORMLAB.</p>
                </div>

                <a href="{{ route('admin.notes.create') }}" class="add-note">
                    + Tambah Catatan
                </a>
            </div>

                        <form action="{{ route('admin.notes.index') }}"
                            method="GET"
                            class="booking-search"
                            style="margin-bottom:12px;">

                            <div class="search-box">
                                <input
                                    type="text"
                                    name="search"
                                    placeholder="Cari judul atau isi catatan..."
                                    value="{{ request('search') }}"
                                >
                            </div>

                            <button type="submit">Cari</button>

                            <a href="{{ route('admin.notes.index') }}">Reset</a>

                        </form>

                        <p class="booking-total" style="margin-bottom:25px;">
                            Menampilkan {{ $notes->count() }} dari {{ $notes->total() }} catatan
                        </p>

            @if(session('success'))
                <div class="success-message">
                    {{ session('success') }}
                </div>
            @endif

            <div class="notes-grid">

                @forelse($notes as $note)

                    <div class="sticky-note {{ $note->color }}">

                        <div>
                            <h3>{{ $note->title }}</h3>
                            <p>{{ $note->content }}</p>
                        </div>

                        <div class="note-actions">

                            <a href="{{ route('admin.notes.edit', $note->id) }}"
                               class="edit-btn">
                                Edit
                            </a>

                            <form action="{{ route('admin.notes.destroy', $note->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus catatan ini?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="delete-btn">
                                    Hapus
                                </button>
                            </form>

                        </div>
                    </div>

                @empty

                    <div class="empty-notes">
                        <h3>Belum ada catatan</h3>
                        <p>Tambahkan catatan pertama untuk Dashboard FORMLAB.</p>
                    </div>

                @endforelse

            </div>
            
        <div class="booking-pagination" style="margin-top:25px;">
            <div>
                Halaman {{ $notes->currentPage() }}
                dari {{ $notes->lastPage() }}
            </div>

            <div>
                {{ $notes->links('pagination::simple-tailwind') }}
            </div>
        </div>

        </div>
    </main>

</div>

</body>
</html>
