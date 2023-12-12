<?php

namespace App\Rules;


use Illuminate\Support\Facades\Http;
use Illuminate\Contracts\Validation\Rule;


class RecaptchaValidationRule implements Rule
{
    public function __construct()
    {
        //
    }
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $endpoint = config('app.google_recaptcha');

        $response = Http::asForm()->post($endpoint['url'], [
            'secret' => $endpoint['secret_key'],
            'response' => $value,
        ])->json();

        if(  $response['success'] && $response['score'] > 0.5) {
            return true;
        }

        return false;
    }

    public function message()
    {
        return __('Something goes wrong. Please contact us directly by phone or email.');
    }
}
