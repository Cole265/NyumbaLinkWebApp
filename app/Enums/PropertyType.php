<?php
// app/Enums/PropertyType.php

namespace App\Enums;

enum PropertyType: string
{
    case RESIDENTIAL = 'residential';
    case LAND = 'land';
    case COMMERCIAL = 'commercial';

    public function label(): string
    {
        return match($this) {
            self::RESIDENTIAL => 'Residential House',
            self::LAND => 'Land/Plot',
            self::COMMERCIAL => 'Commercial Property',
        };
    }

    public function listingFee(): int
    {
        return match($this) {
            self::RESIDENTIAL => 3000,
            self::LAND => 2000,
            self::COMMERCIAL => 5000,
        };
    }
}