<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
//            'cart' => 'required|array',
            // 'cart.*.project_id' => ['required', 'numeric', 'exists:projects,id'],
            // 'cart.*.amount'     => ['required', 'numeric', 'min:1'],
            'payment_type'      => ['nullable'],
            'user_id'           => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'cart.*.amount.gt:100' => __('Amount must be greater than 100'),
        ];
    }
}
