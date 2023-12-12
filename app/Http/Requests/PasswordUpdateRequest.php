<?php

namespace App\Http\Requests;


use App\Models\Setting;

use App\Rules\PasswordValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class PasswordUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $min=Setting::first();
        $min=$min->required_length;
        return [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed','min:'.$min, new PasswordValidationRule()],
        ];
    }

    public function messages()
    {
        return [
            'current_password.required' => __('Current password field is required'),
        ];
    }
}
