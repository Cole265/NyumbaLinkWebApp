<?php
// app/Enums/PaymentMethod.php

namespace App\Enums;

enum PaymentMethod: string
{
    case MOBILE_MONEY = 'mobile_money';
    case AIRTEL_MONEY = 'airtel_money';
    case TNM_MPAMBA = 'tnm_mpamba';
    case BANK_TRANSFER = 'bank_transfer';
    case CASH = 'cash';

    public function label(): string
    {
        return match($this) {
            self::MOBILE_MONEY => 'Mobile Money',
            self::AIRTEL_MONEY => 'Airtel Money',
            self::TNM_MPAMBA => 'TNM Mpamba',
            self::BANK_TRANSFER => 'Bank Transfer',
            self::CASH => 'Cash',
        };
    }

    public function requiresPhoneNumber(): bool
    {
        return match($this) {
            self::AIRTEL_MONEY, self::TNM_MPAMBA, self::MOBILE_MONEY => true,
            default => false,
        };
    }
}