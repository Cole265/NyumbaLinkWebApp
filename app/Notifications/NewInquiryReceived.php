<?php
// app/Notifications/NewInquiryReceived.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewInquiryReceived extends Notification
{
    use Queueable;

    public function __construct(
        public $inquiry,
        public $property,
        public $tenant
    ) {}

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $whatsappLink = 'https://wa.me/' . str_replace(['+', ' '], '', $this->tenant->phone);
        
        return (new MailMessage)
            ->subject('New Inquiry for Your Property')
            ->greeting('You have a new inquiry!')
            ->line("A tenant is interested in your property '{$this->property->title}'.")
            ->line("Tenant: {$this->tenant->name}")
            ->line("Message: {$this->inquiry->message}")
            ->action('View Inquiry', url("/landlord/inquiries/{$this->inquiry->id}"))
            ->line("Or contact them directly on WhatsApp: {$whatsappLink}");
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => 'New Inquiry',
            'message' => "New inquiry for '{$this->property->title}' from {$this->tenant->name}",
            'type' => 'new_inquiry',
            'inquiry_id' => $this->inquiry->id,
            'property_id' => $this->property->id,
            'property_title' => $this->property->title,
            'tenant_name' => $this->tenant->name,
            'tenant_phone' => $this->tenant->phone,
        ];
    }
}