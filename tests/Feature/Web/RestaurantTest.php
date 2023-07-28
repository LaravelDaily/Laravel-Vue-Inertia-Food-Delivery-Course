<?php

namespace Tests\Feature\Web;

use App\Models\City;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;
use Tests\Traits\WithTestingSeeder;

class RestaurantTest extends TestCase
{
    use RefreshDatabase;
    use WithTestingSeeder;

    public function test_admin_can_view_restaurants_index(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this
            ->actingAs($admin)
            ->get(route('admin.restaurants.index'));

        $response->assertInertia(function (Assert $page) {
            return $page->component('Admin/Restaurants/Index')
                ->has('restaurants');
        });
    }

    public function test_admin_can_view_restaurants_create(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this
            ->actingAs($admin)
            ->get(route('admin.restaurants.create'));

        $response->assertInertia(function (Assert $page) {
            return $page->component('Admin/Restaurants/Create')
                ->has('cities');
        });
    }

    public function test_admin_can_store_restaurant()
    {
        $admin = User::factory()->admin()->create();

        $response = $this
            ->actingAs($admin)
            ->postJson(route('admin.restaurants.store'), [
                'restaurant_name' => 'Test Restaurant',
                'email'           => 'test.vendor@example.org',
                'owner_name'      => 'Test Owner Name',
                'city_id'         => City::where('name', 'Vilnius')->first()->id,
                'address'         => 'Dummy address 101',
            ]);

        $response->assertRedirectToRoute('admin.restaurants.index');
    }

    public function test_admin_can_edit_restaurant()
    {
        $admin  = User::factory()->admin()->create();
        $vendor = $this->getVendorUser();

        $response = $this
            ->actingAs($admin)
            ->get(route('admin.restaurants.edit', $vendor->restaurant));

        $response->assertInertia(function (Assert $page) {
            return $page->component('Admin/Restaurants/Edit')
                ->has('restaurant')
                ->has('cities');
        });
    }

    public function test_admin_can_update_restaurant()
    {
        $admin      = User::factory()->admin()->create();
        $vendor     = $this->getVendorUser();
        $restaurant = $vendor->restaurant;

        $response = $this->actingAs($admin)
            ->putJson(route('admin.restaurants.update', $restaurant), [
                'restaurant_name' => 'Updated Restaurant Name',
                'city_id'         => City::where('name', 'Other')->first()->id,
                'address'         => 'Updated Address',
            ]);

        $response->assertRedirectToRoute('admin.restaurants.index');
    }
}
