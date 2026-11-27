<?php

return [
    // Email tujuan notifikasi admin. Kosongkan untuk mematikan email.
    'email' => env('ADMIN_NOTIFICATION_EMAIL'),

    // Jenis notifikasi yang juga dikirim lewat email.
    // (new_booking dan booking_expired cukup di dashboard supaya email tidak banjir)
    'email_types' => [
        'payment_paid',
        'payment_failed',
        'needs_review',
    ],
];
