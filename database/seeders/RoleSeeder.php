<?php

namespace Database\Seeders;

use App\Enums\RoleName;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createAdminRole();
        $this->createVendorRole();
        $this->createCustomerRole();
    }

    protected function createRole(RoleName $role, Collection $permissions): void
    {
        $newRole = Role::create(['name' => $role->value]);
        $newRole->permissions()->sync($permissions);
    }

    protected function createAdminRole(): void
    {
        $permissions = Permission::query()
            ->where('name', 'like', 'user.%')
            ->orWhere('name', 'like', 'restaurant.%')
            ->pluck('id');

        $this->createRole(RoleName::ADMIN, $permissions);
    }

    protected function createVendorRole(): void
    {
        $permissions = Permission::query()
            ->orWhere('name', 'like', 'category.%')
            ->orWhere('name', 'like', 'product.%');

        $this->createRole(RoleName::VENDOR, $permissions->pluck('id'));
    }

    protected function createCustomerRole(): void
    {
        $permissions = Permission::where('name', 'cart.add')->get();

        $this->createRole(RoleName::CUSTOMER, $permissions);
    }
}
