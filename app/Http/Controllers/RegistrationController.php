<?php

namespace App\Http\Controllers;

use App\Interfaces\RegistrationInterface;
use App\Validation\LoginValidated;
use App\Validation\SignupValidated;
use Illuminate\Http\Request;

class RegistrationController extends BaseApiController
{
    private $register;

    public function __construct(RegistrationInterface $register)
    {
        $this->register = $register;
    }

    protected function result($result)
    {
        if (empty($result)) {
            return $this->return_fail(__('auth.login_fail'), []);
        }
        return $this->return_success(__('auth.login_success'), $result);
    }

    public function sendResetLinkEmail(Request $request)
    {
        return $this->register->sendResetLinkEmail($request);
    }

    public function reset(Request $request)
    {

        return $this->register->reset($request);

    }

    public function login(LoginValidated $request)
    {
        $result = $this->register->login($request);
        return $this->result($result);
    }

    public function logout()
    {
        if (auth()->user()->currentAccessToken()) {
            auth()->user()->currentAccessToken()->delete();
        }

        return response()->json(['message' => 'User loged out'], 200);
    }

    public function loginSocial(LoginValidated $request, $socialType)
    {
        $result = $this->register->loginSocial($socialType, $request);
        return $this->result($result);
    }

    public function signup(SignupValidated $request)
    {
        $result = $this->register->signup($request);
        return $this->result($result);
    }

    public function signupSocial(SignupValidated $request, $socialType)
    {
        $result = $this->register->signupSocial($socialType, $request);
        return $this->result($result);
    }


}
