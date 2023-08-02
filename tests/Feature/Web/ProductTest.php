<?php

namespace Tests\Feature\Web;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;
use Tests\Traits\WithTestingSeeder;

class ProductTest extends TestCase
{
    use RefreshDatabase;
    use WithTestingSeeder;

    public function test_vendor_can_view_products_create(): void
    {
        $vendor = $this->getVendorUser();

        $response = $this
            ->actingAs($vendor)
            ->get(route('vendor.products.create'));

        $response->assertInertia(function (AssertableInertia $page) {
            return $page->component('Vendor/Products/Create')
                ->has('categories');
        });
    }

    public function test_vendor_can_store_product(): void
    {
        $vendor = $this->getVendorUser();

        $category = $vendor->restaurant->categories()->first();

        $response = $this
            ->actingAs($vendor)
            ->post(route('vendor.products.store'), [
                'category_id' => $category->id,
                'name'        => 'Pizza',
                'price'       => 2.99,
            ]);

        $response->assertRedirectToRoute('vendor.menu');
    }

    public function test_vendor_can_view_products_edit(): void
    {
        $vendor  = $this->getVendorUser();
        $product = $vendor->restaurant->categories()->first()
            ->products()->first();

        $response = $this
            ->actingAs($vendor)
            ->get(route('vendor.products.edit', $product));

        $response->assertInertia(function (AssertableInertia $page) {
            return $page->component('Vendor/Products/Edit')
                ->has('categories')
                ->has('product');
        });
    }

    public function test_vendor_can_update_product(): void
    {
        $vendor  = $this->getVendorUser();
        $product = $vendor->restaurant->categories()->first()
            ->products()->first();

        $response = $this
            ->actingAs($vendor)
            ->put(route('vendor.products.update', $product), [
                'category_id' => $product->category_id,
                'name'        => 'Awesome Pizza',
                'price'       => 9.99,
            ]);

        $response->assertRedirectToRoute('vendor.menu');
    }

    public function test_vendor_can_destroy_product(): void
    {
        $vendor  = $this->getVendorUser();
        $product = $vendor->restaurant->categories()->first()
            ->products()->first();

        $response = $this
            ->actingAs($vendor)
            ->delete(route('vendor.products.destroy', $product));

        $response->assertRedirectToRoute('vendor.menu');
    }
}
