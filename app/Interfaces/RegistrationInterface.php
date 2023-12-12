<?php

namespace App\Interfaces;

interface RegistrationInterface
{
    public function sendResetLinkEmail($request);

    public function reset($request);

    public function login($request);

    public function loginSocial($socialType, $request);

    public function signup($request);

    public function signupSocial($socialType, $request);

}
