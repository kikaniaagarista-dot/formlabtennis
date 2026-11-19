<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | FORMLAB</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #ffffff;
            color: #222222;
        }
    </style>
</head>

<body>

    <header class="navbar">
        <a href="/" class="logo">FORMLAB<span>.</span></a>

            <nav>
                <a href="/#home">Home</a>
                <a href="{{ route('member.classes') }}">Classes</a>
                <a href="/#coaches">Coaches</a>
                <a href="/#about">About</a>
            </nav>

        <div class="nav-actions">
            @auth
                <form action="{{ url('/logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="login-btn">Logout</button>
                </form>
            @else
                <a href="{{ url('/login') }}" class="login-btn">Login</a>
                <a href="{{ url('/register') }}" class="register-btn">Join Now</a>
            @endauth
        </div>
    </header>

    @yield('content')

<footer class="footer">
    <div class="footer-container">

        <div class="footer-brand">
            <a href="/" class="footer-logo">
                FORMLAB<span>.</span>
            </a>

            <p class="footer-description">
                Shape Your Potential. Move Beyond Limits.
                A space to learn, explore, and evolve through sports.
            </p>
        </div>

        <div class="footer-links">
            <h3>EXPLORE</h3>
            <a href="/#home">Home</a>
            <a href="/#about">Our Story</a>
            <a href="/#coaches">Our Coach</a>
        </div>

        <div class="footer-social">
            <h3>CONNECT WITH US</h3>

            <a href="https://www.instagram.com/formlabtennis/"
               target="_blank" rel="noopener noreferrer">
                Instagram ↗
            </a>

            <a href="https://whatsapp.com/channel/0029VbEjS57BadmVVekGra3G"
               target="_blank" rel="noopener noreferrer">
                WhatsApp Channel ↗
            </a>
        </div>

    </div>

    <div class="footer-bottom">
        <p>© 2026 FORMLAB. All Rights Reserved.</p>
        <p>LEARN. EXPLORE. EVOLVE.</p>
    </div>
</footer>

</body>
</html>