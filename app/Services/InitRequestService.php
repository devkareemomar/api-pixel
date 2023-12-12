<?php

namespace App\Services;

use App\Interfaces\InitRequestInterface;
use App\Models\Language;
use App\Models\SessionData;
use App\Models\User;

class InitRequestService implements InitRequestInterface
{

    public function init($request)
    {
        if ($request['auth'] != null) {
            $user = User::select('users.name', 'email', 'username',
                'languages.name as language_name', 'languages.short_name as language_short_name', 'languages.flag as language_flag',
                'currencies.name as currency_name', 'currencies.code as currency_code')
                ->where('users.id', $request['auth']['id'])
                ->leftJoin('languages', 'languages.id', '=', 'users.language_id')
                ->leftJoin('currencies', 'currencies.id', '=', 'users.currency_id')
                ->first();
            return $user;
        } else {
            $session_id = $request['session_id'];
            $session = SessionData::select('session_id','languages.name as language_name', 'languages.short_name as language_short_name', 'languages.flag as language_flag',
                'currencies.name as currency_name', 'currencies.code as currency_code')
                ->where('session_data.session_id', $session_id)
                ->leftJoin('languages', 'languages.id', '=', 'session_data.language_id')
                ->leftJoin('currencies', 'currencies.id', '=', 'session_data.currency_id')
                ->first();
            return $session;

        }
    }


}
