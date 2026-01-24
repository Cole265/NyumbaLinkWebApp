<?php
// app/Enums/Currency.php

namespace App\Enums;

enum Currency: string
{
    case MWK = 'MWK';
    case USD = 'USD';

    public function label(): string
    {
        return match($this) {
            self::MWK => 'Malawian Kwacha',
            self::USD => 'US Dollar',
        };
    }

    public function symbol(): string
    {
        return match($this) {
            self::MWK => 'MK',
            self::USD => '$',
        };
    }
}