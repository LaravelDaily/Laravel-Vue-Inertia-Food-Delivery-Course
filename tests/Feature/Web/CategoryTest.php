<?php

namespace Tests\Feature\Web;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
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
            ->get(route('vendor.menu'));

        $response->assertInertia(function (Assert $page) {
            return $page->component('Vendor/Menu')
                ->has('categories');
        });
    }

    public function test_vendor_can_view_categories_create(): void
    {
        $vendor   = $this->getVendorUser();
        $response = $this
            ->actingAs($vendor)
            ->get(route('vendor.categories.create'));

        $response->assertInertia(function (Assert $page) {
            return $page->component('Vendor/Categories/Create');
        });
    }

    public function test_vendor_can_store_category(): void
    {
        $vendor   = $this->getVendorUser();
        $response = $this
            ->actingAs($vendor)
            ->postJson(route('vendor.categories.store'), [
                'name' => 'Pizzas',
            ]);

        $response->assertRedirectToRoute('vendor.menu');
    }

    public function test_vendor_can_view_categories_edit(): void
    {
        $vendor   = $this->getVendorUser();
        $category = $vendor->restaurant->categories()->first();

        $response = $this
            ->actingAs($vendor)
            ->get(route('vendor.categories.edit', $category));

        $response->assertInertia(function (Assert $page) {
            return $page->component('Vendor/Categories/Edit');
        });
    }

    public function test_vendor_can_update_category(): void
    {
        $vendor   = $this->getVendorUser();
        $category = $vendor->restaurant->categories()->first();

        $response = $this
            ->actingAs($vendor)
            ->putJson(route('vendor.categories.update', $category), [
                'name' => 'New Category Name',
            ]);

        $response->assertRedirectToRoute('vendor.menu');
    }

    public function test_vendor_can_destroy_category(): void
    {
        $vendor   = $this->getVendorUser();
        $category = $vendor->restaurant->categories()->first();

        $response = $this
            ->actingAs($vendor)
            ->deleteJson(route('vendor.categories.destroy', $category));

        $response->assertRedirectToRoute('vendor.menu');
    }
}
