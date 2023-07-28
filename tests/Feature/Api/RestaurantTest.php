<?php

namespace Tests\Feature\Api;

use App\Models\City;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\WithTestingSeeder;

class RestaurantTest extends TestCase
{
    use RefreshDatabase;
    use WithTestingSeeder;

    public function test_admin_can_view_restaurants(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this
            ->actingAs($admin)
            ->getJson(route('api.admin.restaurants.index'));

        $response->assertOk();
    }

    public function test_admin_can_view_restaurant(): void
    {
        $admin  = User::factory()->admin()->create();
        $vendor = $this->getVendorUser();

        $response = $this
            ->actingAs($admin)
            ->getJson(route('api.admin.restaurants.show', $vendor->restaurant));

        $response->assertOk();
    }

    public function test_admin_can_update_restaurant()
    {
        $admin  = User::factory()->admin()->create();
        $vendor = $this->getVendorUser();

        $response = $this
            ->actingAs($admin)
            ->putJson(route('api.admin.restaurants.update', $vendor->restaurant), [
                'restaurant_name' => 'Updated Restaurant Name',
                'city_id'         => City::where('name', 'Other')->first()->id,
                'address'         => 'Updated Address',
            ]);

        $response->assertAccepted();
    }
}
