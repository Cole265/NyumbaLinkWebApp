<?php
// app/Notifications/PropertyRejected.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PropertyRejected extends Notification
{
    use Queueable;

    public function __construct(
        public $property,
        public $reason
    ) {}

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Property Review Update - NyumbaLink')
            ->greeting('Property Review Update')
            ->line("Your property '{$this->property->title}' requires some changes before it can be published.")
            ->line("Reason: {$this->reason}")
            ->line('Please make the necessary changes and resubmit for review.')
            ->action('Edit Property', url("/landlord/properties/{$this->property->id}/edit"))
            ->line('Thank you for your understanding.');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => 'Property Needs Changes',
            'message' => "Your property '{$this->property->title}' needs revision.",
            'type' => 'property_rejected',
            'property_id' => $this->property->id,
            'property_title' => $this->property->title,
            'reason' => $this->reason,
        ];
    }
}