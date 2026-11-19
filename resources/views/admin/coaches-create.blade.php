
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Coach | FORMLAB</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
<div class="admin-container">
    <main class="main-content">

        <h1>Add New Coach</h1>

        <form action="{{ route('admin.coaches.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if ($errors->any())
                <div style="color: red; margin-bottom: 15px;">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            <label>Coach Name</label>
            <input type="text" name="name" required>

            <label>Specialization</label>
            <input type="text" name="specialization">

            <label>Biography</label>
            <textarea name="bio"></textarea>

            <label>Coach Photo</label>
            <input type="file" name="photo" accept="image/*">

            <button type="submit">Save Coach</button>
            <a href="{{ route('admin.coaches.index') }}">Cancel</a>
        </form>

    </main>
</div>
</body>
</html>
