<?php
// app/Enums/BoostType.php

namespace App\Enums;

enum BoostType: string
{
    case SEVEN_DAY_BOOST = '7_day_boost';
    case FOURTEEN_DAY_FEATURED = '14_day_featured';

    public function label(): string
    {
        return match($this) {
            self::SEVEN_DAY_BOOST => '7-Day Boost',
            self::FOURTEEN_DAY_FEATURED => '14-Day Featured Listing',
        };
    }

    public function price(): int
    {
        return match($this) {
            self::SEVEN_DAY_BOOST => 3000,
            self::FOURTEEN_DAY_FEATURED => 5000,
        };
    }

    public function duration(): int
    {
        return match($this) {
            self::SEVEN_DAY_BOOST => 7,
            self::FOURTEEN_DAY_FEATURED => 14,
        };
    }

    public function description(): string
    {
        return match($this) {
            self::SEVEN_DAY_BOOST => 'Your property appears at the top of search results for 7 days',
            self::FOURTEEN_DAY_FEATURED => 'Your property gets highlighted badge and top placement for 14 days',
        };
    }
}