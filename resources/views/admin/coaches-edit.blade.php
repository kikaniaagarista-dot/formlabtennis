
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Coach | FORMLAB</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
<div class="admin-container">
    <main class="main-content">

        <h1>Edit Coach</h1>

        <form action="{{ route('admin.coaches.update', $coach->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <label>Coach Name</label>
            <input type="text" name="name" value="{{ $coach->name }}" required>

            <label>Specialization</label>
            <input type="text" name="specialization" value="{{ $coach->specialization }}">

            <label>Biography</label>
            <textarea name="bio">{{ $coach->bio }}</textarea>

            <label>Coach Photo</label>

            @if ($coach->photo)
                <p>Current Photo:</p>
                <img src="{{ asset('images/coaches/' . $coach->photo) }}"
                    width="100"
                    style="border-radius: 8px;">
            @endif

            <input type="file" name="photo" accept="image/*">
            <button type="submit">Update Coach</button>
            <a href="{{ route('admin.coaches.index') }}">Cancel</a>
        </form>

    </main>
</div>
</body>
</html>
