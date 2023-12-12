<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class PasswordUpdateController extends Controller
{
    public function __invoke(PasswordUpdateRequest $request)
    {
        auth()->user()->update([
            'password' => bcrypt($request->input('password')),
        ]);

        return response()->json([
            'message' => __('Your password has been updated.'),
        ], ResponseAlias::HTTP_ACCEPTED);
    }
}
