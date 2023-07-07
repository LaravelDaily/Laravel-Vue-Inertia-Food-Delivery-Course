<?php

namespace App\Enums;

enum RoleName: string
{
    case ADMIN    = 'admin';
    case VENDOR   = 'vendor';
    case STAFF    = 'staff';
    case CUSTOMER = 'customer';
}
