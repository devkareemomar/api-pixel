<?php

namespace App\Http\Middleware;

use App\Models\Currency;
use App\Models\Language;
use App\Models\SessionData;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class InitRequestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();
        $session_id = $request->header('session_id') ?? ($request->header('user-agent') . '/' . $request->ip() . '/' . now()->toDateTimeString());
        if (!$token) {
            $session_data = SessionData::firstOrCreate(
                ['session_id' => $session_id],
                [
                    'language_id' => Language::where('is_default', 1)->value('id'),
                    'currency_id' => Currency::where('is_default', 1)->value('id'),
                ]
            );
            $request['session_id'] = $session_data->session_id;
            $request['currency_id'] = $session_data->currency_id;
        } else {
            $user_data = Auth::guard('sanctum')->user();
//            $user_data = PersonalAccessToken::findToken($token)?->tokenable;
            if ($user_data) {
                $auth = [
                    'id' => $user_data->id,
                    'username' => $user_data->username,
                    'email' => $user_data->email,
                    'phone' => $user_data->phone,
                    'language_id' => $user_data->language_id,
                    'currency_id' => $user_data->currency_id,
                ];
                $language = Language::find($auth['language_id'])?->value('short_name');
                $request['auth'] = $auth;
            }
        }
//        App::setLocale($language ?? 'ar');
        return $next($request);
    }
}
