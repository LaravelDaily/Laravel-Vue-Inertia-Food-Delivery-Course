<?php

namespace Tests;

use App\Enums\RoleName;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function getVendorUser(): User
    {
        return User::whereHas('roles', function ($query) {
            $query->where('name', RoleName::VENDOR);
        })->first();
    }
}
