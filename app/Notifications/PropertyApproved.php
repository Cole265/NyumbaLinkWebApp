<?php
// app/Notifications/PropertyApproved.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PropertyApproved extends Notification
{
    use Queueable;

    public function __construct(
        public $property
    ) {}

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Property Approved on Khomolanu')
            ->greeting('Great News!')
            ->line("Your property '{$this->property->title}' has been approved and is now live on Khomolanu.")
            ->line('Tenants can now view and inquire about your property.')
            ->action('View Property', url("/properties/{$this->property->id}"))
            ->line('Consider boosting your property for more visibility!');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => 'Property Approved',
            'message' => "Your property '{$this->property->title}' is now live!",
            'type' => 'property_approved',
            'property_id' => $this->property->id,
            'property_title' => $this->property->title,
        ];
    }
}