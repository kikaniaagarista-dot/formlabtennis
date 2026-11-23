
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

    .payment-heading {
        margin-bottom: 35px;
    }

    .payment-heading span {
        color: #b6ff00;
        font-size: 12px;
        letter-spacing: 3px;
        font-weight: 700;
    }

    .payment-heading h1 {
        font-size: 36px;
        margin: 12px 0;
    }

    .payment-heading p {
        color: #929a91;
    }

    .payment-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px;
    }

    .payment-card {
        background: #1b1e1b;
        border: 1px solid #303630;
        border-radius: 16px;
        padding: 28px;
    }

    .payment-card h3 {
        margin-bottom: 22px;
        font-size: 18px;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        gap: 15px;
        padding: 14px 0;
        border-bottom: 1px solid #303630;
        font-size: 14px;
    }

    .detail-row span:first-child {
        color: #929a91;
    }

    .detail-row strong {
        text-align: right;
    }

    .total-row {
        margin-top: 18px;
        color: #b6ff00;
        font-size: 19px;
    }

    .qris-area {
        text-align: center;
    }

    .qris-area img {
        width: 260px;
        max-width: 100%;
        background: white;
        padding: 10px;
        border-radius: 12px;
        margin: 10px auto 20px;
    }

    .qris-placeholder {
        padding: 45px 15px;
        background: #242824;
        border: 1px dashed #596256;
        border-radius: 12px;
        color: #929a91;
        margin: 15px 0;
    }

    .payment-note {
        color: #929a91;
        font-size: 13px;
        line-height: 1.7;
    }

    .payment-status {
        display: inline-block;
        padding: 8px 14px;
        border-radius: 30px;
        background: #3b3218;
        color: #ffd76a;
        font-size: 12px;
        margin-top: 15px;
    }

    @media (max-width: 700px) {
        .payment-grid {
            grid-template-columns: 1fr;
        }

        .payment-heading h1 {
            font-size: 28px;
        }
    }
</style>

<section class="payment-page">
    <div class="payment-container">

        <div class="payment-heading">
            <span>FORMLAB TENNIS CLUB</span>
            <h1>Complete Your Payment</h1>
            <p>Your booking has been created. Please complete your payment below.</p>
        </div>

        <div class="payment-grid">

            <div class="payment-card">
                <h3>Booking Details</h3>

                <div class="detail-row">
                    <span>Booking ID</span>
                    <strong>#{{ $booking->id }}</strong>
                </div>

                <div class="detail-row">
                    <span>Class</span>
                    <strong>{{ $booking->class_name }}</strong>
                </div>

                <div class="detail-row">
                    <span>Date</span>
                    <strong>{{ $booking->booking_date }}</strong>
                </div>

                <div class="detail-row">
                    <span>Time</span>
                    <strong>{{ $booking->booking_time }}</strong>
                </div>

                <div class="detail-row">
                    <span>Payment Package</span>
                    <strong>{{ $booking->membership_package ?? 'Pay Per Meeting' }}</strong>
                </div>


                <div class="detail-row">
                    <span>Number of Participants</span>
                    <strong>{{ $booking->participants }} People</strong>
                </div>

                <div class="detail-row">
                    <span>Total Sessions</span>
                    <strong>{{ $booking->total_sessions }} Sessions</strong>
                </div>

                <div style="padding: 15px 0; border-bottom: 1px solid #303630;">
                    <p style="color:#929a91; margin-bottom:12px; font-size:14px;">
                        Selected Training Schedules
                    </p>

                    @foreach ($booking->schedules as $schedule)
                        <div style="margin-bottom:12px; font-size:14px;">
                            <strong>
                                {{ $schedule->schedule_date->format('d-m-Y') }}
                            </strong>
                            <p style="color:#929a91; margin-top:5px;">
                                {{ substr($schedule->start_time, 0, 5) }}
                                - {{ substr($schedule->end_time, 0, 5) }} WITA
                            </p>
                            <p style="color:#929a91;">
                                {{ $schedule->location->name ?? '-' }}
                            </p>
                        </div>
                    @endforeach
                </div>


                <div class="detail-row total-row">
                    <span>Total Payment</span>
                    <strong>Rp{{ number_format($booking->total_price, 0, ',', '.') }}</strong>
                </div>

                <div class="payment-status">
                    {{ ucfirst($booking->payment_status ?? 'unpaid') }}
                </div>
            </div>

            <div class="payment-card qris-area">
                <h3>Scan QRIS to Pay</h3>

                @if($setting && $setting->is_active && $setting->qris_image)
                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($setting->qris_image) }}" alt="FORMLAB QRIS">
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

@endsection
