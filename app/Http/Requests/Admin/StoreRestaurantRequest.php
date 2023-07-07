<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreRestaurantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('restaurant.create');
    }

    public function rules(): array
    {
        return [
            'restaurant_name' => ['required', 'string', 'max:255'],
            'email'           => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'owner_name'      => ['required', 'string', 'max:255'],
            'city_id'         => ['required', 'numeric', 'exists:cities,id'],
            'address'         => ['required', 'string', 'max:1000'],
        ];
    }
}
