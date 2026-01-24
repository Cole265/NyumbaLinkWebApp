<?php
// app/Notifications/InquiryResponded.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class InquiryResponded extends Notification
{
    use Queueable;

    public function __construct(
        public $inquiry,
        public $property,
        public $landlord
    ) {}

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $whatsappLink = 'https://wa.me/' . str_replace(['+', ' '], '', $this->landlord->user->phone);
        
        return (new MailMessage)
            ->subject('Landlord Responded to Your Inquiry')
            ->greeting('Good news!')
            ->line("The landlord has responded to your inquiry about '{$this->property->title}'.")
            ->line("Landlord: {$this->landlord->user->name}")
            ->action('View Property', url("/properties/{$this->property->id}"))
            ->line("Contact them on WhatsApp: {$whatsappLink}");
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => 'Inquiry Responded',
            'message' => "Landlord responded to your inquiry about '{$this->property->title}'",
            'type' => 'inquiry_responded',
            'inquiry_id' => $this->inquiry->id,
            'property_id' => $this->property->id,
            'property_title' => $this->property->title,
            'landlord_name' => $this->landlord->user->name,
            'landlord_phone' => $this->landlord->user->phone,
        ];
    }
}
