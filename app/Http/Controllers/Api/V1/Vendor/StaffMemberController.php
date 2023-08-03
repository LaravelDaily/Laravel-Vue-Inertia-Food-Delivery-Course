<?php

namespace App\Http\Controllers\Api\V1\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\StoreStaffMemberRequest;
use App\Http\Resources\Api\V1\Vendor\StaffMemberCollection;
use App\Http\Resources\Api\V1\Vendor\StaffMemberResource;
use App\Services\StaffMemberService;
use Illuminate\Http\Response;

class StaffMemberController extends Controller
{
    public function __construct(public StaffMemberService $staffMemberService)
    {
    }

    public function index()
    {
        $this->authorize('user.viewAny');

        $staffMembers = auth()->user()->restaurant->staff;

        return new StaffMemberCollection($staffMembers);
    }

    public function store(StoreStaffMemberRequest $request): StaffMemberResource
    {
        $member = $this->staffMemberService->createMember(
            $request->user()->restaurant,
            $request->validated()
        );

        return new StaffMemberResource($member);
    }

    public function destroy($staffMemberId): Response
    {
        $this->authorize('user.delete');

        $deleted = $this->staffMemberService->deleteMember(
            auth()->user()->restaurant,
            $staffMemberId
        );

        abort_if(! $deleted, Response::HTTP_NOT_FOUND);

        return response()->noContent();
    }
}
