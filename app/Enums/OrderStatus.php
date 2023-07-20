<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING   = 'pending';
    case PREPARING = 'preparing';
    case READY     = 'ready';
    case CANCELLED = 'cancelled';
}
