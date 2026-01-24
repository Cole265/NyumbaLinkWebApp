<?php
// app/Notifications/VerificationRejected.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class VerificationRejected extends Notification
{
    use Queueable;

    public function __construct(
        public $landlordName,
        public $reason
    ) {}

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('NyumbaLink Verification Update')
            ->greeting("Hello {$this->landlordName},")
            ->line('We were unable to verify your account at this time.')
            ->line("Reason: {$this->reason}")
            ->line('Please review the reason and resubmit your verification documents.')
            ->action('Resubmit Verification', url('/landlord/verification'))
            ->line('If you have questions, please contact our support team.');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => 'Verification Rejected',
            'message' => 'Your verification was rejected. Please review and resubmit.',
            'type' => 'verification_rejected',
            'reason' => $this->reason,
        ];
    }
}