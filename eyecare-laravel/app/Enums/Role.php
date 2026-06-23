<?php

namespace App\Enums;

enum Role: string
{
    case Admin = 'Admin';
    case Staff = 'Staff';
    case Doctor = 'Doctor';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
