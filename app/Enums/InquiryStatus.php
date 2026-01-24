<?php
// app/Enums/InquiryStatus.php

namespace App\Enums;

enum InquiryStatus: string
{
    case PENDING = 'pending';
    case RESPONDED = 'responded';
    case CLOSED = 'closed';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Pending Response',
            self::RESPONDED => 'Responded',
            self::CLOSED => 'Closed',
        };
    }
}