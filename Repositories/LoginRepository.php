<?php

namespace App\Repositories;


use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginRepository
{
    public function login($request)
    {
        $userName_or_Email = $request['user'];
        $password = $request['password'];
        $user = User::where('username', $userName_or_Email)->first();

        if (empty($user)) {
            $user = User::where('email', $userName_or_Email)->first();
        }

        if (empty($user)) {
            return false;
        }

        if (Hash::check($password, $user->password)) {
            $token = $user->createToken('auth_token')->plainTextToken;
            $data = [
                'name' => $user->name,
                'username' => $user->username,
                'token' => $token,
                'email' => $user->email,
            ];
            return $data;
        }
        return false;

    }
}
