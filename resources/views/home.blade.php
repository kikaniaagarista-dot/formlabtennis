
@extends('layouts.app')

@section('title', 'Home')

@section('content')

<section class="hero" id="home">
    <div class="hero-content">
        <p class="hero-label">WELCOME TO FORMLAB TENNIS CLUB</p>

        <h1>
            FIND YOUR FORM.<br>
            BUILD YOUR GAME.
        </h1>

        <p class="hero-description">
            Improve your skills, discover your potential,
            and enjoy every moment on the court.
        </p>

        <div class="hero-buttons">
            <a href="{{ route('member.classes') }}" class="hero-btn">
                Explore Classes
            </a>
            <a href="#about" class="hero-link">
                Discover More →
            </a>
        </div>
    </div>
</section>

<section class="about" id="about">
    <div class="about-content">
        <p class="section-label">DISCOVER OUR STORY</p>

        <h2>More Than a Game. A Journey of Growth.</h2>

        <p>
            FORMLAB was built on a simple belief:
            everyone has the potential to grow, improve,
            and discover more within themselves.
        </p>

        <p>
            Inspired by the idea of shaping potential
            and continuous exploration, FORMLAB brings
            together movement, learning, and meaningful
            experiences in one space.
        </p>

        <p>
            We believe progress is not only about the
            results you achieve, but also the discipline
            you build, the challenges you embrace,
            and the journey you experience along the way.
        </p>

        <p>
            Starting with tennis, FORMLAB creates
            opportunities for people to explore sports,
            develop their abilities, and become part
            of a growing community.
        </p>

        <h3>We don't just create a place to play.
            We create a space to grow.</h3>

        <p class="about-quote">
            "The journey of a thousand miles begins with a single step."
        <p class="section-label">
            FORM YOUR JOURNEY. MOVE BEYOND LIMITS.
        </p>
    </div>
</section>

<section class="coaches" id="coaches">
    <div class="coaches-heading">
        <p class="section-label">MEET THE TEAM</p>
        <h2>Learn From The Best.</h2>
        <p>Meet the people who will guide your tennis journey.</p>
    </div>

    <div class="coach-container">
        <div class="coach-card">
            <div class="coach-photo">
                <img src="{{ asset('images/coach-1.png') }}" alt="FORMLAB Coach">
            </div>
           <div class="coach-info">
                <h2 class="coach-label">OUR HEAD COACH</h2>

                <h4>Every Journey Starts Somewhere.</h4>

                <p class="coach-description">
                    Memulai perjalanan di dunia tenis sejak 2014, setiap pengalaman
                    menjadi bagian dari proses untuk terus belajar, bertumbuh,
                    dan mengembangkan kemampuan di lapangan.
                </p>

                <div class="coach-achievement">
                    <h2>2017</h2>
                    <h3>SEMIFINALIST</h3>
                    <p>Wali Kota Samarinda Tennis Tournament</p>
                    <span>Junior U-12</span>
                </div>

                <p class="coach-quote">
                    A journey shaped by passion, strengthened by dedication,
                    and driven by consistency.
                </p>

                <h3 class="coach-closing">The journey continues.</h3>
            </div>
        </div>
    </div>
</section>

@endsection
