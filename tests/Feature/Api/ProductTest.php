<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\WithTestingSeeder;

class ProductTest extends TestCase
{
    use RefreshDatabase;
    use WithTestingSeeder;

    public function test_vendor_can_store_product(): void
    {
        $vendor = $this->getVendorUser();

        $category = $vendor->restaurant->categories()->first();

        $response = $this
            ->actingAs($vendor)
            ->postJson(route('api.vendor.products.store'), [
                'category_id' => $category->id,
                'name'        => 'Pizza',
                'price'       => 2.99,
            ]);

        $response->assertCreated();
    }

    public function test_vendor_can_view_product(): void
    {
        $vendor  = $this->getVendorUser();
        $product = $vendor->restaurant->categories()->first()
            ->products()->first();

        $response = $this
            ->actingAs($vendor)
            ->get(route('api.vendor.products.show', $product));

        $response->assertOk();
    }

    public function test_vendor_can_update_product(): void
    {
        $vendor  = $this->getVendorUser();
        $product = $vendor->restaurant->categories()->first()
            ->products()->first();

        $response = $this
            ->actingAs($vendor)
            ->putJson(route('api.vendor.products.update', $product), [
                'category_id' => $product->category_id,
                'name'        => 'Awesome Pizza',
                'price'       => 9.99,
            ]);

        $response->assertAccepted();
    }
}
