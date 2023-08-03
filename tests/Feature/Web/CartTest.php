<?php

namespace Tests\Feature\Web;

use App\Models\Product;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;
use Tests\Traits\WithTestingSeeder;

class CartTest extends TestCase
{
    use RefreshDatabase;
    use WithTestingSeeder;

    public function test_customer_can_view_cart()
    {
        $customer = User::factory()->customer()->create();

        $response = $this
            ->actingAs($customer)
            ->get(route('customer.cart.index'));

        $response->assertInertia(function (Assert $page) {
            return $page->component('Customer/Cart');
        });
    }

    public function test_customer_can_add_item_to_cart(): void
    {
        $customer = User::factory()->customer()->create();
        $product  = Product::first();

        $response = $this
            ->actingAs($customer)
            ->post(route('customer.cart.add', $product));

        $response->assertRedirect()->assertSessionDoesntHaveErrors();
    }

    public function test_customer_can_remove_item_from_cart(): void
    {
        $customer = User::factory()->customer()->create();
        $product  = Product::first();

        $this->actingAs($customer)->post(route('customer.cart.add', $product));

        $firstInCart = (new CartService)->items()[0]['uuid'];
        $response    = $this
            ->actingAs($customer)
            ->post(route('customer.cart.remove', $firstInCart));

        $response->assertRedirect()->assertSessionDoesntHaveErrors();
    }

    public function test_customer_cant_remove_non_existing_item_from_cart(): void
    {
        $customer = User::factory()->customer()->create();
        $product  = Product::first();

        $this->actingAs($customer)->post(route('customer.cart.add', $product));
        $response = $this->actingAs($customer)->post(route('customer.cart.remove', '74cf4ca6-7140-4ba7-909d-f7a7d1c4c7b'));

        $response->assertNotFound();
    }
}
