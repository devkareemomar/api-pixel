<?php

namespace App\Validation;

use App\Models\Setting;
use App\Rules\RecaptchaValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;

class LoginValidated extends FormRequest
{
    public function rules(): array
    {
        return $this->url() == url('api/login') ? $this->login() : $this->loginSocial();
    }

    protected function login()
    {
        $data = [
            'user' => 'required|min:3|max:255',
            'password' => 'required|min:3',
        ];
        if (Setting::select('use_captcha_on_login')->first()->use_captcha_on_login == 1) {
            $data+=[
                'captcha_token'=>new RecaptchaValidationRule()
            ];
        }
        return $data;
    }

    protected function loginSocial()
    {
        return [
            'token' => 'required',
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
        // return [
        //     'user.required' => __('Email field is required.'),
        // ];
    }
}
