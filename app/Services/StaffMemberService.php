<?php

namespace App\Services;

use App\Enums\RoleName;
use App\Models\Restaurant;
use App\Models\Role;
use App\Models\User;
use App\Notifications\RestaurantStaffInvitation;
use Illuminate\Support\Facades\DB;

class StaffMemberService
{
    public function createMember(Restaurant $restaurant, array $attributes): User
    {
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

        return $member;
    }

    public function deleteMember(Restaurant $restaurant, $staffMemberId): bool
    {
        $member = $restaurant->staff()->find($staffMemberId);

        if ($member === null) {
            return false;
        }

        $member->roles()->sync([]);
        $member->delete();

        return true;
    }
}
