<?php

namespace App\Http\Requests\Vendor;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreStaffMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('user.create');
    }

    public function rules(): array
    {
        return [
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
        ];
    }
}
