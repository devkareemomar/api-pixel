<?php

namespace App\Validation;

use App\Models\Setting;
use App\Rules\PasswordValidationRule;
use App\Rules\RecaptchaValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rules\Password;

class SignupValidated extends FormRequest
{
    public function rules(): array
    {
        return $this->url() == url('api/signup') ? $this->signup() : $this->signupSocial();
    }

    protected function signup()
    {
        $min = Setting::first();
        $min = $min->required_length ?? 5;
        $data = [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'username' => 'nullable|unique:users,username',
            'phone' => 'nullable|unique:users,phone|numeric|digits_between:8,12',
            'password' => ['required', 'confirmed'],
            // 'password' => ['required', 'confirmed', 'min:' . $min, new PasswordValidationRule()],
        ];
        if (Setting::select('use_captcha_on_login')->first()->use_captcha_on_registration == 1) {
            $data += [
                'captcha_token' => new RecaptchaValidationRule()
            ];
        }
        return $data;
    }

    protected function signupSocial()
    {
        return [
            'name' => 'required',
            'email' => 'required|email',
            'username' => 'nullable',
            'phone' => 'nullable|numeric|digits_between:8,12',
            'token' => 'required',
            'facebook' => 'nullable',
            'twitter' => 'nullable',
            'google' => 'nullable',
            'apple' => 'nullable',
        ];
    }

    public function failedValidation(Validator $validator): JsonResponse
    {
        $errors = $validator->errors()->all(); // Get all validation error messages

        throw new HttpResponseException(
            response()->json([
                'message' => 'Validation errors',
                'errors' => $errors,
            ], 422)
        );
    }

    public function messages()
    {
        return [
            // 'username.unique' => __('auth.username_unique'),
            // 'email.unique' => __('auth.email_unique'),
            // 'phone.unique' => __('auth.phone_unique'),
            // 'username.required' => __('auth.username_required'),
            // 'user.required' => __('auth.user_required'),
            // 'password.required' => __('auth.password_required'),
            // 'email.required' => __('auth.email_required'),
        ];
    }
}
