
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Classes | FORMLAB Tennis</title>

    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
            background: #101010;
            color: white;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 5%;
            background: #080808;
            border-bottom: 1px solid #333;
            flex-wrap: wrap;
            gap: 15px;
        }

        .logo {
            color: #b6ff00;
            font-size: 24px;
            font-weight: bold;
        }

        nav {
            display: flex;
            gap: 25px;
            flex-wrap: wrap;
        }

        nav a {
            color: white;
            text-decoration: none;
            font-size: 14px;
        }

        nav a:hover,
        nav a.active {
            color: #b6ff00;
        }

        .main-content {
            padding: 40px 5%;
        }

        h1 {
            color: #b6ff00;
        }

        .classes-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .class-card {
            padding: 25px;
            background: #1d1d1d;
            border-radius: 12px;
            border: 1px solid #333;
        }

        .class-card h2 {
            color: #b6ff00;
        }

        .price {
            font-weight: bold;
        }

        .book-button {
            display: inline-block;
            margin-top: 15px;
            padding: 12px 20px;
            background: #b6ff00;
            color: black;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
        }

        .book-button:hover {
            background: white;
        }

        @media (max-width: 600px) {
            header {
                align-items: flex-start;
                flex-direction: column;
            }

            nav {
                gap: 15px;
            }

            .main-content {
                padding: 30px 5%;
            }
        }
    </style>
</head>

<body>

<header>
    <div class="logo">FORMLAB</div>

    <nav>
        <a href="/member">Dashboard</a>

        <a href="{{ route('member.classes') }}" class="active">
            Classes
        </a>

        <a href="{{ route('member.bookings.index') }}">
            My Bookings
        </a>
    </nav>
</header>

<div class="main-content">

    <h1>Choose Your Tennis Class</h1>
    <p>Find the right training program for your game.</p>

    <div class="classes-container">

        @forelse ($classes as $class)

            <div class="class-card">

                <h2>{{ $class->name }}</h2>

                <p>{{ $class->description }}</p>

                <p class="price">
                    Rp {{ number_format($class->price, 0, ',', '.') }}
                </p>

                @if ($class->type === 'regular')
                    <a href="{{ route('member.booking.create', $class->id) }}"
                       class="book-button">
                        Book This Class
                    </a>
                @elseif ($class->type === 'private')
                    <a href="{{ route('member.private.create', $class->id) }}"
                       class="book-button">
                        View Available Schedule
                    </a>
                @endif

            </div>

        @empty

            <p>No classes available yet.</p>

        @endforelse

    </div>

</div>

</body>
</html>
