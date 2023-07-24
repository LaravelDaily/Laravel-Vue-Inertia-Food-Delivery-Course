<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING   = 'pending';
    case PREPARING = 'preparing';
    case READY     = 'ready';
    case CANCELLED = 'cancelled';

    public static function toArray(): array
    {
        $cases = [];

        foreach (self::cases() as $case) {
            $cases[$case->name] = $case->value;
        }

        return $cases;
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
