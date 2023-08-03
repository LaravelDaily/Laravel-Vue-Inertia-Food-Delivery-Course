<?php

namespace Tests\Feature\Web;

use App\Enums\OrderStatus;
use App\Models\Product;
use App\Models\Restaurant;
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

    public function test_staff_members_can_see_orders(): void
    {
        $staff = User::factory()->staff()->create();

        $response = $this
            ->actingAs($staff)
            ->get(route('staff.orders.index'));

        $response->assertInertia(function (AssertableInertia $page) {
            return $page->component('Staff/Orders')
                ->has('current_orders')
                ->has('past_orders')
                ->has('order_status');
        });
    }

    public function test_staff_can_update_order(): void
    {
        $customer   = User::factory()->customer()->create();
        $restaurant = Restaurant::first();
        $product    = $restaurant->categories()->first()
            ->products()->first();

        $this->actingAs($customer)->post(route('customer.cart.add', $product));
        $this->actingAs($customer)->post(route('customer.orders.store'));

        $staff = $restaurant->staff()->first();
        $order = $restaurant->orders()->first();

        $request = $this->actingAs($staff)->put(route('staff.orders.update', $order), [
            'status' => OrderStatus::PREPARING->value,
        ]);

        $request->assertRedirect()->assertSessionDoesntHaveErrors();
    }

    public function test_staff_cant_update_order_with_invalid_status(): void
    {
        $customer   = User::factory()->customer()->create();
        $restaurant = Restaurant::first();
        $product    = $restaurant->categories()->first()
            ->products()->first();

        $this->actingAs($customer)->post(route('customer.cart.add', $product));
        $this->actingAs($customer)->post(route('customer.orders.store'));

        $staff = $restaurant->staff()->first();
        $order = $restaurant->orders()->first();

        $request = $this->actingAs($staff)->put(route('staff.orders.update', $order), [
            'status' => 'invalid_random_status',
        ]);

        $request->assertRedirect()->assertSessionHasErrors(['status']);
    }
}
