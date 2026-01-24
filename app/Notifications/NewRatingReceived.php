<?php
// app/Notifications/NewRatingReceived.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewRatingReceived extends Notification
{
    use Queueable;

    public function __construct(
        public $rating,
        public $property
    ) {}

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $stars = str_repeat('⭐', round($this->rating->overall_rating));
        
        return (new MailMessage)
            ->subject('You Received a New Rating!')
            ->greeting('New Rating Received')
            ->line("You received a {$this->rating->overall_rating} star rating for '{$this->property->title}'")
            ->line("Rating: {$stars}")
            ->line("Review: {$this->rating->review}")
            ->action('View All Ratings', url('/landlord/ratings'))
            ->line('Keep up the great work!');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => 'New Rating',
            'message' => "You received a {$this->rating->overall_rating} star rating!",
            'type' => 'new_rating',
            'rating_id' => $this->rating->id,
            'property_id' => $this->property->id,
            'property_title' => $this->property->title,
            'overall_rating' => $this->rating->overall_rating,
            'review' => $this->rating->review,
        ];
    }
}