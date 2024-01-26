<?php

namespace App\Services;


use App\Events\NotificationEvent;
use App\Interfaces\RegistrationInterface;
use App\Models\LogLogin;
use App\Models\SessionData;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class RegistrationService implements RegistrationInterface
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    protected function logLogin($is_success, $user_id)
    {
        LogLogin::create([
            'user_id' => $user_id,
            'user_ip' => request()->ip(),
            'browser_name' => request()->header('user-agent'),
            'is_success' => $is_success,
        ]);
        return;
    }

    protected function returnData($user_data)
    {
        $token = $user_data->createToken('auth_token')->plainTextToken;
        $data = [
            'token' => $token,
            'id' => $user_data->id,
            'name' => $user_data->name,
            'email' => $user_data->email,
            'phone' => $user_data->phone,
        ];
        return $data;
    }

    public function sendResetLinkEmail($request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $response = Password::sendResetLink($request->only('email'));
        if ($response === Password::RESET_LINK_SENT) {
            return response()->json(['message' => 'Password reset link sent to your email']);
        }

        return response()->json(['error' => 'Failed to send reset link'], 500);
    }

    public function reset($request)
    {
        $request->validate([
            'email' => 'required|email',
            // 'token' => 'required|string',
            'password' => 'required|min:8|confirmed',
        ]);

        // $response = Password::reset(
        //     $request->only('email', 'password', 'password_confirmation', 'token'),
        //     function (User $user, string $password) {
        //         $user->forceFill([
        //             'password' => Hash::make($password)
        //         ])->setRememberToken(Str::random(60));

        //         $user->save();

        //         event(new PasswordReset($user));
        //     }
        // );

        $user =  User::where('id', auth()->user()->id)->update([
            'password' => Hash::make($request->password)
        ]);

        if ($user) {
            event(new PasswordReset($user));

            $user_data = User::where('email', $request->email)->first();
            return response()->json(self::returnData($user_data));
        }

        return response()->json(['error' => 'Failed to reset password'], 500);
    }

    public function login($request)
    {
        $data = [];
        $userName_or_Email = $request['user'];
        $password = $request['password'];
        $user_data = $this->user->where('username', $userName_or_Email)->first();
        if (empty($user_data)) {
            $user_data = $this->user->where('email', $userName_or_Email)->first();
        }
        if (empty($user_data)) {
            return $data;
        }
        if (Hash::check($password, $user_data->password)) {
            self::logLogin(true, $user_data->id);
            return self::returnData($user_data);
        }
        self::logLogin(false, $user_data->id);
        return $data;
    }

    public function loginSocial($socialType, $request)
    {
        $data = [];
        $tokenID = $request['token'];
        if ($socialType == 'facebook') {
            $user_data = $this->user->where('facebook', $tokenID)->first();
        } elseif ($socialType == 'google') {
            $user_data = $this->user->where('google', $tokenID)->first();
        } elseif ($socialType == 'twitter') {
            $user_data = $this->user->where('twitter', $tokenID)->first();
        } elseif ($socialType == 'apple') {
            $user_data = $this->user->where('apple', $tokenID)->first();
        }
        if (empty($user_data)) {
            return $data;
        } else {
            return self::returnData($user_data);
        }
    }

    protected function session_data($request)
    {
        $session_id = $request->header('session_id');
        $session = SessionData::where('session_id', $session_id)->first();
        $request['language_id'] = $session->language_id ?? null;
        $request['currency_id'] = $session->currency_id ?? null;
        return $request;
    }

    public function signup($request)
    {
        $data = [];
        $this->session_data($request);
        $input = $request->all();
        $input['password'] = Hash::make($request['password']);
        if (!isset($input['username'])) {
            $input['username'] = $input['email'];
        }


        $user_data = $this->user->create($input);
        if (empty($user_data)) {
            return $data;
        }
        $user_data->assignRole('customer');
        event(new NotificationEvent($user_data->id, 'new_user'));

        return self::returnData($user_data);
    }

    public function signupSocial($socialType, $request)
    {
        $data = [];
        $this->session_data($request);
        $input = $request->all();
        $input['password'] = Hash::make(Str::random(10));
        $tokenID = $request['token'];
        if ($socialType == 'facebook') {
            $input['facebook'] = $tokenID;
        } elseif ($socialType == 'google') {
            $input['google'] = $tokenID;
        } elseif ($socialType == 'twitter') {
            $input['twitter'] = $tokenID;
        } elseif ($socialType == 'apple') {
            $input['apple'] = $tokenID;
        }
        if (!isset($input['username'])) {
            $input['username'] = $input['email'];
        }
        $user_data = $this->user->where('email', $input['email'])->first();

        if(!$user_data){
            $user_data = $this->user->create($input);
        }

        if (empty($user_data)) {
            return $data;
        }

        $user_data->assignRole('customer');
        event(new NotificationEvent($user_data->id, 'new_user'));

        return self::returnData($user_data);
    }
}
