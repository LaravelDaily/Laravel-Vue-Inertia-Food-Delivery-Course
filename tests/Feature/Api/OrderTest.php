<?php

namespace Tests\Feature\Api;

use App\Enums\OrderStatus;
use App\Models\Product;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\WithTestingSeeder;

class OrderTest extends TestCase
{
    use RefreshDatabase;
    use WithTestingSeeder;

    public function test_customer_can_place_order(): void
    {
        $customer = User::factory()->customer()->create();
        $product  = Product::first();

        $this->actingAs($customer)->postJson(route('api.customer.cart.add', $product));

        $response = $this->actingAs($customer)->postJson(route('api.customer.orders.store'));

        $response->assertCreated();
    }

    public function test_customer_cant_place_empty_order()
    {
        $customer = User::factory()->customer()->create();

        $response = $this->actingAs($customer)->postJson(route('api.customer.orders.store'));

        $response->assertUnprocessable();
    }

    public function test_customer_can_see_orders()
    {
        $customer = User::factory()->customer()->create();
        $product  = Product::first();

        $this->actingAs($customer)->postJson(route('api.customer.cart.add', $product));
        $this->actingAs($customer)->postJson(route('api.customer.orders.store'));

        $response = $this
            ->actingAs($customer)
            ->get(route('customer.orders.index'));

        $response->assertOk();
    }

    public function test_staff_members_can_see_orders(): void
    {
        $staff = User::factory()->staff()->create();

        $response = $this
            ->actingAs($staff)
            ->get(route('api.staff.orders.index'));

        $response->assertOk();
    }

    public function test_staff_members_can_past_orders(): void
    {
        $staff = User::factory()->staff()->create();

        $response = $this
            ->actingAs($staff)
            ->get(route('api.staff.orders.past'));

        $response->assertOk();
    }

    public function test_staff_can_update_order(): void
    {
        $customer   = User::factory()->customer()->create();
        $restaurant = Restaurant::first();
        $product    = $restaurant->categories()->first()
            ->products()->first();

        $this->actingAs($customer)->postJson(route('api.customer.cart.add', $product));
        $this->actingAs($customer)->postJson(route('api.customer.orders.store'));

        $staff = $restaurant->staff()->first();
        $order = $restaurant->orders()->first();

        $request = $this->actingAs($staff)->putJson(route('api.staff.orders.update', $order), [
            'status' => OrderStatus::PREPARING->value,
        ]);

        $request->assertAccepted();
    }

    public function test_staff_cant_update_order_with_invalid_status(): void
    {
        $customer   = User::factory()->customer()->create();
        $restaurant = Restaurant::first();
        $product    = $restaurant->categories()->first()
            ->products()->first();

        $this->actingAs($customer)->postJson(route('api.customer.cart.add', $product));
        $this->actingAs($customer)->postJson(route('api.customer.orders.store'));

        $staff = $restaurant->staff()->first();
        $order = $restaurant->orders()->first();

        $request = $this->actingAs($staff)->putJson(route('api.staff.orders.update', $order), [
            'status' => 'invalid_random_status',
        ]);

        $request->assertUnprocessable();
    }
}
