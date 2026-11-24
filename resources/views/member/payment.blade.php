
@extends('layouts.app')

@section('title', 'Payment')

@section('content')

<style>
    .payment-page {
        min-height: 100vh;
        background: #101210;
        color: #fff;
        padding: 60px 20px;
    }

    .payment-container {
        max-width: 950px;
        margin: auto;
    }

    .payment-title {
        text-align: center;
        margin-bottom: 35px;
    }

    .payment-title h1 {
        font-size: 32px;
        font-weight: 800;
        color: #b6ff00;
        margin-bottom: 10px;
    }

    .payment-title p {
        color: #aaa;
        font-size: 14px;
    }

    .payment-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px;
        align-items: start;
    }

    .payment-card {
        background: #1b1e1b;
        border: 1px solid #303630;
        border-radius: 16px;
        padding: 28px;
    }

    .payment-card h3 {
        color: #b6ff00;
        font-size: 20px;
        margin-bottom: 22px;
    }

    .payment-detail {
        display: flex;
        justify-content: space-between;
        gap: 15px;
        padding: 13px 0;
        border-bottom: 1px solid #343834;
        font-size: 14px;
    }

    .payment-detail span:first-child {
        color: #aaa;
    }

    .payment-detail strong {
        text-align: right;
        overflow-wrap: anywhere;
    }

    .payment-total {
        margin-top: 20px;
        padding: 17px;
        background: #292e25;
        border-radius: 10px;
        display: flex;
        justify-content: space-between;
        gap: 10px;
        font-weight: 800;
    }

    .payment-total strong {
        color: #b6ff00;
        text-align: right;
    }

    .qris-area {
        text-align: center;
    }

    .qris-area img {
        display: block;
        width: 100%;
        max-width: 300px;
        height: auto;
        margin: 0 auto 20px;
        border-radius: 12px;
        background: #fff;
        padding: 10px;
    }

    .download-qris-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-top: 5px;
        padding: 13px 25px;
        background: #b6ff00;
        color: #111 !important;
        font-weight: 700;
        font-size: 15px;
        text-decoration: none !important;
        border-radius: 10px;
        transition: 0.2s ease;
    }

    .download-qris-btn:hover {
        background: #d0ff55;
        transform: translateY(-2px);
    }

    .qris-placeholder {
        background: #292e25;
        border: 1px dashed #777;
        border-radius: 12px;
        padding: 35px 20px;
        color: #aaa;
        line-height: 1.7;
    }

    .payment-note {
        color: #aaa;
        font-size: 13px;
        line-height: 1.7;
        margin-top: 20px;
        text-align: left;
    }

    .payment-note strong {
        color: #fff;
    }

    .payment-timer {
        margin-bottom: 25px;
        padding: 22px;
        background: #1b1e1b;
        border: 1px solid #b6ff00;
        border-radius: 14px;
        text-align: center;
    }

    .payment-timer h3 {
        color: #b6ff00; ;
        font-size: 17px;
        margin-bottom: 8px;
    }

    .payment-timer p {
        color: #aaa;
        font-size: 13px;
        margin-bottom: 12px;
    }

    .timer-countdown {
        font-size: 38px;
        font-weight: 800;
        color: #ffffff;
        letter-spacing: 3px;
    }

    .timer-expired {
        color: #ff6464;
    }

    @media (max-width: 768px) {
        .timer-countdown {
            font-size: 32px;
        }
    }

    @media (max-width: 768px) {
        .payment-page {
            padding: 35px 15px;
        }

        .payment-grid {
            grid-template-columns: 1fr;
        }

        .payment-title h1 {
            font-size: 26px;
        }

        .payment-card {
            padding: 22px;
        }
    }
</style>

<section class="payment-page">
    <div class="payment-container">

        <div class="payment-title">
            <h1>Complete Your Payment</h1>
            <p>Please review your booking details and complete your payment.</p>
        </div>


        @if($booking->status === 'pending' && $booking->payment_status === 'unpaid')
            <div class="payment-timer" id="paymentTimer"
                data-expiry="{{ $booking->created_at->copy()->addMinutes(15)->toIso8601String() }}">

                <h3>Complete Your Payment Before Time Runs Out</h3>
                <p>Please complete your payment within 15 minutes.</p>

                <div class="timer-countdown" id="countdown">15:00</div>
            </div>
        @endif

        <div class="payment-grid">

            {{-- BOOKING DETAILS --}}
            <div class="payment-card">
                <h3>Booking Details</h3>

                <div class="payment-detail">
                    <span>Booking ID</span>
                    <strong>#{{ $booking->id }}</strong>
                </div>

                <div class="payment-detail">
                    <span>Class</span>
                    <strong>{{ $booking->class_name }}</strong>
                </div>

                <div class="payment-detail">
                    <span>Booking Date</span>
                    <strong>{{ $booking->booking_date }}</strong>
                </div>

                <div class="payment-detail">
                    <span>Booking Time</span>
                    <strong>{{ $booking->booking_time }}</strong>
                </div>

                <div class="payment-detail">
                    <span>Participants</span>
                    <strong>{{ $booking->participants }}</strong>
                </div>

                <div class="payment-detail">
                    <span>Total Sessions</span>
                    <strong>{{ $booking->total_sessions }}</strong>
                </div>

                <div class="payment-detail">
                    <span>Payment Status</span>
                    <strong>{{ ucfirst($booking->payment_status) }}</strong>
                </div>

                <div class="payment-total">
                    <span>Total Payment</span>
                    <strong>
                        Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                    </strong>
                </div>
            </div>

            {{-- QRIS PAYMENT --}}
            <div class="payment-card qris-area">
                <h3>Scan QRIS to Pay</h3>

                @if($setting && $setting->is_active && $setting->qris_image)

                    @php
                        $qrisUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($setting->qris_image);
                    @endphp

                    <img
                        src="{{ $qrisUrl }}"
                        alt="FORMLAB QRIS"
                    >

                    <a
                        href="{{ route('member.booking.download-qris', $booking->id) }}"
                        class="download-qris-btn"
                    >
                        <i class="fas fa-download"></i>
                        Download QRIS
                    </a>

                @else

                    <div class="qris-placeholder">
                        QRIS is currently unavailable.
                        <br>
                        Please contact the administrator.
                    </div>

                @endif

                <p class="payment-note">
                    Scan the QRIS code using your preferred payment application.
                    Make sure the payment amount matches the total shown.
                </p>

                <p class="payment-note">
                    Payment Name:
                    <strong>{{ $setting->payment_name ?? 'FORMLAB Tennis' }}</strong>
                </p>
            </div>

        </div>
    </div>
</section>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const timer = document.getElementById('paymentTimer');
    const countdown = document.getElementById('countdown');

    if (!timer || !countdown) return;

    const expiry = new Date(timer.dataset.expiry).getTime();

    function updateCountdown() {
        const remaining = Math.max(0, Math.floor((expiry - Date.now()) / 1000));

        const minutes = Math.floor(remaining / 60);
        const seconds = remaining % 60;

        countdown.textContent =
            String(minutes).padStart(2, '0') + ':' +
            String(seconds).padStart(2, '0');

        if (remaining <= 60) {
            countdown.classList.add('timer-expired');
        }

        if (remaining <= 0) {
            countdown.textContent = 'EXPIRED';
            clearInterval(interval);

            setTimeout(function () {
                window.location.reload();
            }, 1500);
        }
    }

    updateCountdown();

    const interval = setInterval(updateCountdown, 1000);
});
</script>

@endsection
