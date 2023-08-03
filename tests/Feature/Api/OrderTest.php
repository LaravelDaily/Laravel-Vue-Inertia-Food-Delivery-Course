<?php

namespace Tests\Feature\Api;

use App\Models\Product;
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
}
