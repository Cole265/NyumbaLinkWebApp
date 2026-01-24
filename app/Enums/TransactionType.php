<?php
// app/Enums/TransactionType.php

namespace App\Enums;

enum TransactionType: string
{
    case REGISTRATION_FEE = 'registration_fee';
    case LISTING_FEE = 'listing_fee';
    case BOOST_FEE = 'boost_fee';

    public function label(): string
    {
        return match($this) {
            self::REGISTRATION_FEE => 'Registration Fee',
            self::LISTING_FEE => 'Listing Fee',
            self::BOOST_FEE => 'Boost Fee',
        };
    }

    public function amount(): int
    {
        return match($this) {
            self::REGISTRATION_FEE => 10000,
            self::LISTING_FEE => 0, // Variable by property type
            self::BOOST_FEE => 0, // Variable by boost type
        };
    }
}