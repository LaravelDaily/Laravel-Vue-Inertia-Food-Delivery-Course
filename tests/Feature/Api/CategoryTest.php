<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\WithTestingSeeder;

class CategoryTest extends TestCase
{
    use RefreshDatabase;
    use WithTestingSeeder;

    public function test_vendor_can_view_categories(): void
    {
        $vendor   = $this->getVendorUser();
        $response = $this
            ->actingAs($vendor)
            ->getJson(route('api.vendor.categories.index'));

        $response->assertOk();
    }

    public function test_vendor_can_store_category(): void
    {
        $vendor   = $this->getVendorUser();
        $response = $this
            ->actingAs($vendor)
            ->postJson(route('api.vendor.categories.store'), [
                'name' => 'Pizzas',
            ]);

        $response->assertCreated();
    }

    public function test_vendor_can_update_category(): void
    {
        $vendor   = $this->getVendorUser();
        $category = $vendor->restaurant->categories()->first();

        $response = $this
            ->actingAs($vendor)
            ->putJson(route('api.vendor.categories.update', $category), [
                'name' => 'New Category Name',
            ]);

        $response->assertAccepted();
    }

    public function test_vendor_can_destroy_category(): void
    {
        $vendor   = $this->getVendorUser();
        $category = $vendor->restaurant->categories()->first();

        $response = $this
            ->actingAs($vendor)
            ->deleteJson(route('api.vendor.categories.destroy', $category));

        $response->assertNoContent();
    }
}
