<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case Paid = 'Paid';
    case Pending = 'Pending';
    case Refunded = 'Refunded';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
