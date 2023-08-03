<?php

namespace Tests\Feature\Web;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
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

        $this->actingAs($customer)->post(route('customer.cart.add', $product));

        $response = $this->actingAs($customer)->post(route('customer.orders.store'));

        $response->assertRedirectToRoute('customer.orders.index');
    }

    public function test_customer_cant_place_empty_order(): void
    {
        $customer = User::factory()->customer()->create();

        $response = $this->actingAs($customer)->post(route('customer.orders.store'));

        $response->assertSessionHasErrors(['restaurant_id', 'items', 'total']);
    }

    public function test_customer_can_see_orders(): void
    {
        $customer = User::factory()->customer()->create();
        $product  = Product::first();

        $this->actingAs($customer)->post(route('customer.cart.add', $product));
        $this->actingAs($customer)->post(route('customer.orders.store'));

        $response = $this
            ->actingAs($customer)
            ->get(route('customer.orders.index'));

        $response->assertInertia(function (AssertableInertia $page) {
            return $page->component('Customer/Orders')
                ->has('orders');
        });
    }
}
