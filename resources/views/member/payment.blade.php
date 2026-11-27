@extends('layouts.app')

@section('title', 'Payment')

@section('content')

@php
    // Mode uji coba: kalau MIDTRANS_ADMIN_ONLY=true, hanya admin yang melihat tombol Pay Now.
    $midtransVisible = ! config('midtrans.admin_only') || auth()->user()?->role === 'admin';
@endphp

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

    /* ===== MIDTRANS (GoPay / QRIS otomatis) ===== */
    .gateway-area {
        text-align: center;
    }

    .gateway-methods {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-bottom: 18px;
    }

    .gateway-methods span {
        padding: 6px 14px;
        border: 1px solid #3a403a;
        border-radius: 999px;
        font-size: 13px;
        color: #ddd;
    }

    .midtrans-pay-btn {
        width: 100%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 15px 25px;
        background: #b6ff00;
        color: #111;
        font-weight: 800;
        font-size: 16px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        transition: 0.2s ease;
    }

    .midtrans-pay-btn:hover:not(:disabled) {
        background: #d0ff55;
        transform: translateY(-2px);
    }

    .midtrans-pay-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .pay-message {
        display: none;
        margin-top: 14px;
        padding: 12px 14px;
        border-radius: 10px;
        font-size: 13px;
        line-height: 1.6;
        text-align: left;
    }

    .pay-message.info {
        display: block;
        background: #292e25;
        color: #ddd;
    }

    .pay-message.success {
        display: block;
        background: #24331a;
        color: #b6ff00;
    }

    .pay-message.error {
        display: block;
        background: #3a1f1f;
        color: #ff8a8a;
    }

    .pay-divider {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 28px 0 22px;
        color: #777;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .pay-divider::before,
    .pay-divider::after {
        content: "";
        flex: 1;
        height: 1px;
        background: #343834;
    }

    .paid-badge {
        padding: 22px;
        background: #24331a;
        border: 1px solid #b6ff00;
        border-radius: 12px;
        color: #b6ff00;
        font-weight: 700;
        line-height: 1.7;
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

            {{-- PAYMENT --}}
            <div class="payment-card qris-area">

                @if($booking->payment_status === 'paid')

                    <div class="paid-badge">
                        <i class="fas fa-check-circle"></i>
                        Payment received. Your booking is confirmed.
                    </div>

                @elseif($midtransVisible && $booking->status === 'pending' && $booking->payment_status === 'unpaid')

                    {{-- MIDTRANS: GoPay / QRIS otomatis --}}
                    <div class="gateway-area">
                        <h3>Pay with GoPay or QRIS</h3>

                        <div class="gateway-methods">
                            <span>GoPay</span>
                            <span>QRIS</span>
                        </div>

                        <button
                            type="button"
                            id="payButton"
                            class="midtrans-pay-btn"
                            data-url="{{ route('member.booking.snap-token', $booking->id) }}"
                            data-csrf="{{ csrf_token() }}"
                        >
                            <i class="fas fa-wallet"></i>
                            Pay Now
                        </button>

                        <div id="payMessage" class="pay-message"></div>

                        <p class="payment-note">
                            Your payment is verified automatically.
                            The booking status will update once the payment succeeds.
                        </p>
                    </div>

                    <div class="pay-divider">or pay manually</div>

                @endif

                @if($booking->payment_status !== 'paid')
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
                @endif
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


{{-- ===== MIDTRANS SNAP ===== --}}
@if($booking->status === 'pending' && $booking->payment_status === 'unpaid')
<script
    src="{{ config('midtrans.is_production')
        ? 'https://app.midtrans.com/snap/snap.js'
        : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
    data-client-key="{{ config('midtrans.client_key') }}">
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const payButton = document.getElementById('payButton');
    const payMessage = document.getElementById('payMessage');

    if (!payButton) return;

    const originalLabel = payButton.innerHTML;

    function showMessage(type, text) {
        payMessage.className = 'pay-message ' + type;
        payMessage.textContent = text;
    }

    function resetButton() {
        payButton.disabled = false;
        payButton.innerHTML = originalLabel;
    }

    payButton.addEventListener('click', async function () {
        payButton.disabled = true;
        payButton.innerHTML = 'Preparing payment...';
        showMessage('info', 'Connecting to the payment service...');

        try {
            // 1. Minta snap token ke Laravel
            const response = await fetch(payButton.dataset.url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': payButton.dataset.csrf,
                    'Accept': 'application/json',
                },
            });

            const data = await response.json().catch(() => ({}));

            if (!response.ok || !data.snap_token) {
                showMessage('error', data.message || 'Payment could not be started. Please try again.');
                resetButton();
                return;
            }

            payMessage.className = 'pay-message';

            // 2. Buka jendela pembayaran Midtrans
            window.snap.pay(data.snap_token, {
                onSuccess: function () {
                    // Status "paid" TIDAK diatur di sini.
                    // Yang mengubah status hanya webhook dari Midtrans (Tahap 5).
                    showMessage('success', 'Payment received. Updating your booking...');
                    setTimeout(() => window.location.reload(), 3000);
                },
                onPending: function () {
                    showMessage('info', 'Waiting for your payment. Complete it in GoPay or your payment app.');
                    resetButton();
                },
                onError: function () {
                    showMessage('error', 'Payment failed. Please try again.');
                    resetButton();
                },
                onClose: function () {
                    showMessage('info', 'Payment window closed. Click "Pay Now" to continue.');
                    resetButton();
                },
            });
        } catch (error) {
            showMessage('error', 'Connection problem. Please check your internet and try again.');
            resetButton();
        }
    });
});
</script>
@endif

@endsection