<?php
// app/Enums/UserRole.php

namespace App\Enums;

enum UserRole: string
{
    case LANDLORD = 'landlord';
    case TENANT = 'tenant';
    case ADMIN = 'admin';

    public function label(): string
    {
        return match($this) {
            self::LANDLORD => 'Landlord',
            self::TENANT => 'Tenant',
            self::ADMIN => 'Administrator',
        };
    }
}