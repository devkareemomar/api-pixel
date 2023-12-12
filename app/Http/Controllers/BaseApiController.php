<?php

namespace App\Http\Controllers;

use App\Http\Controllers;
use Illuminate\Http\JsonResponse;

class BaseApiController extends Controller
{
    public function return_success($message, $data = null): JsonResponse
    {
        return response()->json([
            'message'   => $message,
            'data'      => $data,
        ], 200);
    }

    public function return_fail($message, $validation): JsonResponse
    {
        return response()->json([
            'message'   => $message,
            'Validation'=> $validation,
        ], 422);
    }
}
