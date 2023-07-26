<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('order.create');
    }

    public function rules(): array
    {
        return [
            'restaurant_id'         => ['required', 'exists:restaurants,id'],
            'items'                 => ['required', 'array'],
            'items.*.id'            => ['required', 'exists:products,id'],
            'items.*.name'          => ['required', 'string'],
            'items.*.price'         => ['required', 'integer'],
            'items.*.restaurant_id' => ['required', 'exists:restaurants,id', 'in:' . $this->restaurant_id],
            'total'                 => ['required', 'integer', 'gt:0'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $cart = session('cart');

        $this->merge([
            'restaurant_id' => $cart['restaurant_id'],
            'items'         => $cart['items'],
            'total'         => $cart['total'],
        ]);
    }
}
