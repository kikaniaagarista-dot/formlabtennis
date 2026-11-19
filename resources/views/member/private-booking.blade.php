
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Private Class | FORMLAB</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f4f6f3;
            color: #151915;
        }

        /* HEADER */
        .header {
            background: #101510;
            color: white;
            padding: 20px 7%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            color: #a4e52e;
            font-size: 28px;
            font-weight: 800;
            letter-spacing: 2px;
        }

        .header-link {
            color: white;
            text-decoration: none;
            font-size: 14px;
        }

        .header-link:hover {
            color: #a4e52e;
        }

        /* HERO */
        .hero {
            background: #101510;
            color: white;
            padding: 55px 7% 70px;
        }

        .hero-label {
            display: inline-block;
            color: #a4e52e;
            border: 1px solid #a4e52e;
            padding: 7px 14px;
            border-radius: 30px;
            font-size: 12px;
            margin-bottom: 20px;
        }

        .hero h1 {
            font-size: 38px;
            margin-bottom: 15px;
        }

        .hero p {
            color: #c2c9c1;
            max-width: 650px;
            line-height: 1.7;
            font-size: 15px;
        }

        .hero-price {
            margin-top: 22px;
            font-size: 23px;
            font-weight: bold;
            color: #a4e52e;
        }

        /* MAIN */
        .main-container {
            max-width: 1100px;
            margin: -25px auto 60px;
            padding: 0 25px;
            position: relative;
        }

        .section-heading {
            margin-bottom: 25px;
        }

        .section-heading h2 {
            font-size: 25px;
            margin-bottom: 8px;
        }

        .section-heading p {
            color: #777;
            font-size: 14px;
        }

        /* SCHEDULE CARD */
        .schedule-list {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .schedule-card {
            background: white;
            border: 1px solid #e5e9e3;
            border-radius: 14px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.04);
            transition: 0.2s;
        }

        .schedule-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }

        .available-label {
            display: inline-block;
            background: #edf8dc;
            color: #477c0e;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 18px;
        }

        .schedule-date {
            font-size: 19px;
            font-weight: bold;
            margin-bottom: 18px;
        }

        .schedule-time {
            background: #101510;
            color: white;
            padding: 16px;
            border-radius: 10px;
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .schedule-detail {
            display: flex;
            gap: 12px;
            margin-bottom: 15px;
            align-items: flex-start;
        }

        .detail-icon {
            color: #76ad1c;
            font-size: 18px;
        }

        .detail-text {
            flex: 1;
        }

        .detail-text small {
            display: block;
            color: #888;
            margin-bottom: 5px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .detail-text p {
            font-size: 14px;
            line-height: 1.5;
        }

        .schedule-note {
            border-top: 1px solid #eee;
            padding-top: 15px;
            margin-top: 10px;
            color: #666;
            font-size: 13px;
            line-height: 1.6;
        }

        /* EMPTY STATE */
        .empty-state {
            background: white;
            border-radius: 14px;
            padding: 45px 20px;
            text-align: center;
            border: 1px solid #e5e9e3;
        }

        .empty-state h3 {
            margin-bottom: 10px;
        }

        .empty-state p {
            color: #777;
            line-height: 1.6;
        }

        /* FOOTER */
        .footer {
            background: #101510;
            color: #aaa;
            text-align: center;
            padding: 25px;
            font-size: 12px;
        }

        .footer strong {
            color: #a4e52e;
        }

        @media (max-width: 700px) {
            .header {
                padding: 18px 5%;
            }

            .hero {
                padding: 40px 6% 60px;
            }

            .hero h1 {
                font-size: 29px;
            }

            .schedule-list {
                grid-template-columns: 1fr;
            }

            .main-container {
                padding: 0 18px;
            }
        }

        .whatsapp-button {
            display: block;
            width: 100%;
            text-align: center;
            background: #174d38;
            color: white;
            padding: 14px;
            border-radius: 9px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 20px;
            transition: 0.2s;
        }

        .whatsapp-button:hover {
            background: #a4e52e;
            color: #101510;
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <header class="header">
        <div class="logo">FORMLAB</div>

        <a href="{{ route('member.classes') }}" class="header-link">
            ← Back to Classes
        </a>
    </header>

    <!-- HERO -->
    <section class="hero">

        <span class="hero-label">PRIVATE TRAINING PROGRAM</span>

        <h1>{{ $class->name }}</h1>

        <p>{{ $class->description }}</p>

        <div class="hero-price">
            Rp {{ number_format($class->price, 0, ',', '.') }}
            <span style="font-size:13px; color:#ccc; font-weight:normal;">
                / session
            </span>
        </div>

    </section>

    <!-- MAIN CONTENT -->
    <main class="main-container">

        <div class="section-heading">
            <h2>Pilih Jadwal Private Class</h2>
            <p>
                Temukan jadwal latihan yang sesuai dengan waktu kamu.
            </p>
        </div>

        @if ($schedules->count() > 0)

            <div class="schedule-list">

                @foreach ($schedules as $schedule)

                    <div class="schedule-card">

                        <span class="available-label">
                            AVAILABLE SCHEDULE
                        </span>

                        <div class="schedule-date">
                            {{ \Carbon\Carbon::parse($schedule->schedule_date)->translatedFormat('l, d F Y') }}
                        </div>

                        <div class="schedule-time">
                            {{ substr($schedule->start_time, 0, 5) }}
                            –
                            {{ substr($schedule->end_time, 0, 5) }}
                        </div>

                        <div class="schedule-detail">
                            <span class="detail-icon">⌖</span>

                            <div class="detail-text">
                                <small>Training Location</small>

                                <p>
                                    {{ $schedule->location->name ?? 'Lokasi belum tersedia' }}
                                </p>
                            </div>
                        </div>

                        @if ($schedule->note)
                            <div class="schedule-note">
                                <strong>Training Notes</strong>
                                <p>{{ $schedule->note }}</p>
                            </div>
                        @endif

                        <a
                            href="https://wa.me/6281999887764?text={{ urlencode('Halo Admin FORMLAB, saya ingin menanyakan booking ' . $class->name . ' pada tanggal ' . \Carbon\Carbon::parse($schedule->schedule_date)->format('d-m-Y') . ' pukul ' . substr($schedule->start_time, 0, 5) . ' - ' . substr($schedule->end_time, 0, 5) . '. Apakah jadwal ini tersedia?') }}"
                            target="_blank"
                            class="whatsapp-button"
                        >
                            Hubungi Admin via WhatsApp
                        </a>

                        </div>

                @endforeach

            </div>

        @else

            <div class="empty-state">
                <h3>Belum Ada Jadwal Tersedia</h3>

                <p>
                    Saat ini belum ada jadwal Private Class yang tersedia.
                    Silakan periksa kembali jadwal di lain waktu.
                </p>
            </div>

        @endif

    </main>

    <!-- FOOTER -->
    <footer class="footer">
        © {{ date('Y') }} <strong>FORMLAB Tennis Club</strong>.
        All Rights Reserved.
    </footer>

</body>
</html>
