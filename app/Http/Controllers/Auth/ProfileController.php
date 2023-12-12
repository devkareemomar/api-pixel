<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return response()->json($request->user()->only('username', 'email', 'phone', 'name'));
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore(auth()->user())],
            'phone' => ['required', 'string', 'max:25' ,Rule::unique('users')->ignore(auth()->user())],
        ]);

        auth()->user()->update($validatedData);

        return response()->json($validatedData, ResponseAlias::HTTP_ACCEPTED);
    }
}
