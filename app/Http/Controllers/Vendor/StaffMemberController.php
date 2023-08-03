<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\StoreStaffMemberRequest;
use App\Services\StaffMemberService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response as HttpResponse;
use Inertia\Inertia;
use Inertia\Response;

class StaffMemberController extends Controller
{
    public function __construct(public StaffMemberService $staffMemberService)
    {
    }

    public function index(): Response
    {
        $this->authorize('user.viewAny');

        return Inertia::render('Vendor/Staff/Show', [
            'staff' => auth()->user()->restaurant->staff,
        ]);
    }

    public function store(StoreStaffMemberRequest $request): RedirectResponse
    {
        $this->staffMemberService->createMember(
            $request->user()->restaurant,
            $request->validated()
        );

        return back();
    }

    public function destroy($staffMemberId)
    {
        $this->authorize('user.delete');

        $deleted = $this->staffMemberService->deleteMember(
            auth()->user()->restaurant,
            $staffMemberId
        );

        abort_if(! $deleted, HttpResponse::HTTP_NOT_FOUND);

        return back();
    }
}
