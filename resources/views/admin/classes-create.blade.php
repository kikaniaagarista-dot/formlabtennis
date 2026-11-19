
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Class | FORMLAB Admin</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    <style>
        .class-form-wrapper {
            max-width: 850px;
            margin: 35px auto;
        }

        .class-form-card {
            background: #fff;
            padding: 35px;
            border-radius: 14px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.06);
        }

        .class-form-card h2 {
            margin-bottom: 8px;
            color: #171717;
        }

        .form-description {
            color: #777;
            margin-bottom: 30px;
        }

        .class-form-group {
            margin-bottom: 22px;
        }

        .class-form-group label {
            display: block;
            margin-bottom: 9px;
            font-weight: 600;
            color: #292929;
        }

        .class-form-group input,
        .class-form-group textarea,
        .class-form-group select {
            width: 100%;
            padding: 13px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            box-sizing: border-box;
            font-family: inherit;
        }

        .class-form-group input:focus,
        .class-form-group textarea:focus,
        .class-form-group select:focus {
            border-color: #a3e635;
            box-shadow: 0 0 0 3px rgba(163,230,53,0.15);
        }

        .class-form-group textarea {
            min-height: 120px;
            resize: vertical;
        }

        .class-form-actions {
            display: flex;
            gap: 12px;
            margin-top: 30px;
        }

        .save-class-btn {
            background: #a3e635;
            color: #171717;
            border: none;
            padding: 13px 24px;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
        }

        .save-class-btn:hover {
            background: #8bc727;
        }

        .cancel-class-btn {
            background: #eee;
            color: #333;
            padding: 13px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
        }

        .cancel-class-btn:hover {
            background: #ddd;
        }

        .class-errors {
            background: #fff0f0;
            color: #c62828;
            padding: 12px 18px;
            border-radius: 8px;
            margin-bottom: 20px;
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
        <a href="/admin/classes" class="active">Classes</a>
        <a href="/admin/coaches">Coaches</a>
        <a href="/admin/payments">Payments</a>
        <a href="/admin/settings">Settings</a>
        <a href="/">Back to Website</a>
    </aside>

    <main class="main-content">

        <div class="topbar">
            <h1>Class Management</h1>
            <div class="admin-profile">Administrator</div>
        </div>

        <div class="class-form-wrapper">

            <div class="class-form-card">

                <h2>Add New Class</h2>
                <p class="form-description">
                    Create a new tennis training program for FORMLAB members.
                </p>

                @if ($errors->any())
                    <div class="class-errors">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('admin.classes.store') }}" method="POST">
                    @csrf

                    <div class="class-form-group">
                        <label>Class Name</label>
                        <input type="text" name="name"
                            value="{{ old('name') }}"
                            placeholder="Enter class name" required>
                    </div>

                    <div class="class-form-group">
                        <label>Class Type</label>
                        <select name="type" required>
                            <option value="regular" {{ old('type') == 'regular' ? 'selected' : '' }}>
                                Regular
                            </option>
                            <option value="private" {{ old('type') == 'private' ? 'selected' : '' }}>
                                Private
                            </option>
                        </select>
                    </div>

                    <div class="class-form-group">
                        <label>Description</label>
                        <textarea name="description"
                            placeholder="Describe the tennis class">{{ old('description') }}</textarea>
                    </div>

                    <div class="class-form-group">
                        <label>Price (Rp)</label>
                        <input type="number" name="price"
                            value="{{ old('price') }}"
                            min="0" placeholder="Enter class price" required>
                    </div>

                    <div class="class-form-group">
                        <label>Capacity</label>
                        <input type="number" name="capacity"
                            value="{{ old('capacity') }}"
                            min="1" placeholder="Enter class capacity" required>
                    </div>

                    <div class="class-form-actions">
                        <button type="submit" class="save-class-btn">
                            Save Class
                        </button>

                        <a href="{{ route('admin.classes.index') }}"
                            class="cancel-class-btn">
                            Cancel
                        </a>
                    </div>

                </form>

            </div>
        </div>

    </main>
</div>

</body>
</html>
