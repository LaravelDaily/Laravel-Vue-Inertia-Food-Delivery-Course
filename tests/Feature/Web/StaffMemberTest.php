<?php

namespace Tests\Feature\Web;

use App\Events\StaffMemberCreated;
use App\Models\Category;
use App\Models\Product;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Inertia\Testing\AssertableInertia;
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
            ->get(route('vendor.staff-members.index'));

        $response->assertInertia(function (AssertableInertia $page) {
            return $page->component('Vendor/Staff/Show')
                ->has('staff');
        });
    }

    public function test_vendor_can_add_new_staff_member(): void
    {
        $vendor = $this->getVendorUser();

        $response = $this
            ->actingAs($vendor)
            ->post(route('vendor.staff-members.store', [
                'name'  => 'John Doe',
                'email' => 'john@example.org',
            ]));

        $response->assertRedirect()->assertSessionDoesntHaveErrors();
    }

    public function test_vendor_cant_add_existing_staff_member(): void
    {
        $vendor = $this->getVendorUser();
        $staff  = $vendor->restaurant->staff()->first();

        Event::fake();

        $response = $this
            ->actingAs($vendor)
            ->post(route('vendor.staff-members.store', [
                'name'  => 'John Doe',
                'email' => $staff->email,
            ]));

        $response->assertRedirect()->assertSessionHasErrors(['email']);
        Event::assertNotDispatched(StaffMemberCreated::class);
    }

    public function test_vendor_can_delete_existing_staff_member(): void
    {
        $vendor = $this->getVendorUser();
        $staff  = $vendor->restaurant->staff()->first();

        $response = $this
            ->actingAs($vendor)
            ->delete(route('vendor.staff-members.destroy', $staff->id));

        $response->assertRedirect()->assertSessionHasNoErrors();
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
            ->delete(route('vendor.staff-members.destroy', $anotherStaffMember->id));

        $response->assertNotFound();
    }
}
