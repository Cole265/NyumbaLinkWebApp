<?php
// app/Enums/PropertyStatus.php

namespace App\Enums;

enum PropertyStatus: string
{
    case DRAFT = 'draft';
    case PENDING_REVIEW = 'pending_review';
    case PUBLISHED = 'published';
    case RENTED = 'rented';
    case SOLD = 'sold';
    case EXPIRED = 'expired';

    public function label(): string
    {
        return match($this) {
            self::DRAFT => 'Draft',
            self::PENDING_REVIEW => 'Pending Review',
            self::PUBLISHED => 'Published',
            self::RENTED => 'Rented',
            self::SOLD => 'Sold',
            self::EXPIRED => 'Expired',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::DRAFT => 'gray',
            self::PENDING_REVIEW => 'yellow',
            self::PUBLISHED => 'green',
            self::RENTED => 'blue',
            self::SOLD => 'purple',
            self::EXPIRED => 'red',
        };
    }

    public function isActive(): bool
    {
        return $this === self::PUBLISHED;
    }
}