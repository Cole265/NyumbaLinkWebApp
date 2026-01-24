<?php
// app/Notifications/ListingExpiringSoon.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ListingExpiringSoon extends Notification
{
    use Queueable;

    public function __construct(
        public $listing,
        public $property,
        public $daysLeft
    ) {}

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Property Listing Expiring Soon')
            ->greeting('Listing Expiration Notice')
            ->line("Your property listing '{$this->property->title}' will expire in {$this->daysLeft} days.")
            ->line('After expiration, your property will no longer be visible to tenants.')
            ->action('Renew Listing', url("/landlord/properties/{$this->property->id}/renew"))
            ->line('You can also boost your listing for better visibility!');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => 'Listing Expiring Soon',
            'message' => "Your listing '{$this->property->title}' expires in {$this->daysLeft} days",
            'type' => 'listing_expiring',
            'listing_id' => $this->listing->id,
            'property_id' => $this->property->id,
            'property_title' => $this->property->title,
            'days_left' => $this->daysLeft,
            'expiry_date' => $this->listing->expiry_date,
        ];
    }
}