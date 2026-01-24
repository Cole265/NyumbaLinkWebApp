<?php
// app/Notifications/VerificationApproved.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class VerificationApproved extends Notification
{
    use Queueable;

    public function __construct(
        public $landlordName,
        public $adminNotes = null
    ) {}

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your NyumbaLink Account is Verified!')
            ->greeting("Hello {$this->landlordName}!")
            ->line('Congratulations! Your landlord account has been verified.')
            ->line('You can now start listing your properties on NyumbaLink.')
            ->action('Create Your First Property', url('/landlord/properties/create'))
            ->line('Thank you for joining NyumbaLink!');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => 'Account Verified',
            'message' => 'Your landlord account has been verified. You can now list properties!',
            'type' => 'verification_approved',
            'admin_notes' => $this->adminNotes,
        ];
    }
}