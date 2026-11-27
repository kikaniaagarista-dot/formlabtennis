<?php

namespace App\Notifications;

use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Notifikasi untuk admin.
 * - Ke akun admin  → disimpan di database (tampil di dashboard).
 * - Ke alamat email → dikirim sebagai email (muncul di Gmail + HP).
 */
class AdminAlert extends Notification
{
    public function __construct(
        public string $type,     // new_booking | payment_paid | payment_failed | booking_expired | needs_review
        public string $title,
        public string $message,
        public ?string $url = null,
    ) {
    }

    public function via(object $notifiable): array
    {
        return $notifiable instanceof AnonymousNotifiable ? ['mail'] : ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'    => $this->type,
            'title'   => $this->title,
            'message' => $this->message,
            'url'     => $this->url,
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('[FORMLAB] ' . $this->title)
            ->greeting($this->title)
            ->line($this->message);

        if ($this->type === 'needs_review') {
            $mail->error();
        }

        return $mail
            ->action('Open Admin Panel', url($this->url ?? '/admin/notifications'))
            ->line('This is an automatic notification from the FORMLAB Tennis website.');
    }
}
