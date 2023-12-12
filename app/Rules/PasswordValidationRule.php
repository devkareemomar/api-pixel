<?php

namespace App\Rules;

use App\Models\Setting;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PasswordValidationRule implements ValidationRule
{

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        $setting = Setting::first();
        $roles = [];
        if ($setting->use_default_settings == 1) {

        } else {
            if ($setting->require_digit == 1) {
                if (!preg_match('/[0-9]/', $value)) {
                    $roles[] = ['message' => __('Digit is required(0-9)')];
                }
            }
            if ($setting->require_lowercase == 1) {
                if (!preg_match('/[a-z]/', $value)) {
                    $roles[] = ['message' => __('Lowercase is required(a-z)')];
                }
            }
            if ($setting->require_non_alphanumeric == 1) {
                if (!preg_match('/[$\_\&\@\*\~\%]/', $value)) {
                    $roles[] = ['message' => __('Special Characters is required($, _, &, @, *, ~, %)')];
                }
            }
            if ($setting->require_uppercase == 1) {
                if (!preg_match('/[A-Z]/', $value)) {
                    $roles[] = ['message' => __('Uppercase is required (A-Z)')];
                }
            }
        }
        foreach ($roles as $role) {
            $fail(__($role['message']));

        }
    }
}
