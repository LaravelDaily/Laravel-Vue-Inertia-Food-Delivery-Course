<?php

namespace App\Http\Controllers\Api\V1\Vendor;

use App\Enums\RoleName;
use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\StoreStaffMemberRequest;
use App\Http\Resources\Api\V1\Vendor\StaffMemberCollection;
use App\Http\Resources\Api\V1\Vendor\StaffMemberResource;
use App\Models\Role;
use App\Notifications\RestaurantStaffInvitation;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class StaffMemberController extends Controller
{
    public function index()
    {
        $this->authorize('user.viewAny');

        $staffMembers = auth()->user()->restaurant->staff;

        return new StaffMemberCollection($staffMembers);
    }

    public function store(StoreStaffMemberRequest $request): StaffMemberResource
    {
        $restaurant = $request->user()->restaurant;
        $attributes = $request->validated();

        $member = DB::transaction(function () use ($attributes, $restaurant) {
            $user = $restaurant->staff()->create([
                'name'     => $attributes['name'],
                'email'    => $attributes['email'],
                'password' => '',
            ]);

            $user->roles()->sync(Role::where('name', RoleName::STAFF->value)->first());

            return $user;
        });

        $member->notify(new RestaurantStaffInvitation($restaurant->name));

        return new StaffMemberResource($member);
    }

    public function destroy($staffMemberId): Response
    {
        $this->authorize('user.delete');

        $restaurant = auth()->user()->restaurant;
        $member     = $restaurant->staff()->findOrFail($staffMemberId);

        $member->roles()->sync([]);
        $member->delete();

        return response()->noContent();
    }
}
