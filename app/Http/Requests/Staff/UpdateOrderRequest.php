<?php

namespace App\Http\Requests\Staff;

use App\Enums\OrderStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('order.update');
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(OrderStatus::values())],
        ];
    }
}
