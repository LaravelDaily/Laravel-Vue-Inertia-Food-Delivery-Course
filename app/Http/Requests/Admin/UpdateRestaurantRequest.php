<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateRestaurantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('restaurant.update');
    }

    public function rules(): array
    {
        return [
            'restaurant_name' => ['required', 'string', 'max:255'],
            'city_id'         => ['required', 'numeric', 'exists:cities,id'],
            'address'         => ['required', 'string', 'max:1000'],
        ];
    }
}
