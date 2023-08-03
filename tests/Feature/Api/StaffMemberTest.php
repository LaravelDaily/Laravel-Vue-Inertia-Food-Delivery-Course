<?php

namespace Tests\Feature\Api;

use App\Events\StaffMemberCreated;
use App\Models\Category;
use App\Models\Product;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use Tests\Traits\WithTestingSeeder;

class StaffMemberTest extends TestCase
{
    use RefreshDatabase;
    use WithTestingSeeder;

    public function test_vendor_can_list_staff_members(): void
    {
        $vendor   = $this->getVendorUser();
        $response = $this
            ->actingAs($vendor)
            ->get(route('api.vendor.staff-members.index'));

        $response->assertOk();
    }

    public function test_vendor_can_add_new_staff_member(): void
    {
        $vendor = $this->getVendorUser();

        $response = $this
            ->actingAs($vendor)
            ->postJson(route('api.vendor.staff-members.store', [
                'name'  => 'John Doe',
                'email' => 'john@example.org',
            ]));

        $response->assertCreated();
    }

    public function test_vendor_cant_add_existing_staff_member(): void
    {
        $vendor = $this->getVendorUser();
        $staff  = $vendor->restaurant->staff()->first();

        Event::fake();

        $response = $this
            ->actingAs($vendor)
            ->postJson(route('api.vendor.staff-members.store', [
                'name'  => 'John Doe',
                'email' => $staff->email,
            ]));

        $response->assertUnprocessable();
        Event::assertNotDispatched(StaffMemberCreated::class);
    }

    public function test_vendor_can_delete_existing_staff_member(): void
    {
        $vendor = $this->getVendorUser();
        $staff  = $vendor->restaurant->staff()->first();

        $response = $this
            ->actingAs($vendor)
            ->deleteJson(route('api.vendor.staff-members.destroy', $staff->id));

        $response->assertNoContent();
    }

    public function test_vendor_cant_delete_staff_member_it_doesnt_belong_to_restaurant(): void
    {
        $vendor = $this->getVendorUser();

        $anotherVendor = User::factory()
            ->vendor()
            ->has(
                Restaurant::factory()->has(
                    Category::factory()->has(
                        Product::factory()
                    )
                )
                    ->has(User::factory()->staff(), 'staff')
            )
            ->create();

        $anotherStaffMember = $anotherVendor->restaurant->staff()->first();

        $response = $this
            ->actingAs($vendor)
            ->deleteJson(route('api.vendor.staff-members.destroy', $anotherStaffMember->id));

        $response->assertNotFound();
    }
}
