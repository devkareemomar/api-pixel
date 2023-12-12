<?php

namespace App\Http\Controllers\Auth;

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Http\Controllers\Controller;
use App\Models\SocialiteProvider;
use App\Models\User;
use App\Traits\SocialiteProvidersTrait;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    use AuthenticatesUsers, SocialiteProvidersTrait;


    public function getSocialRedirect(Request $request, string $provider)
    {
        $providerKey = Config::get('services.' . $provider);

        if (empty($providerKey)) {
            abort(419);
        }

        $url = null;
        $token = null;
        $state = null;
        $user = null;
        $scopes = [];
        $with = [];

        if (auth('sanctum')->check()) {
            $user = auth('sanctum')->user();
        }


        if ($user) {
            $token = $user->createToken($provider . '-user-token')->plainTextToken;
            $state = Crypt::encrypt($token);
        } else {
            $state = Crypt::encrypt(config('app.key'));
        }

        $with = ['state' => $state];


        if ($provider == 'twitter') {
            $url = $this->twitterUserAuthenticationUrl($state);
        } else {
            $url = Socialite::driver($provider)
                ->stateless()
                ->with($with)
                ->scopes($scopes)
                ->redirect()
                ->getTargetUrl();
        }

        return response()->json([
            'url' => $url,
        ]);
    }

    public function handleSocialCallback(Request $request, string $provider)
    {
        $denied = $request->denied ? $request->denied : null;

//        if ($denied != null || $denied != '') {
//            throw new SocialProviderDeniedException;
//        }
        if ($provider == 'twitter') {
            $socialUser = $this->twitterUserAuthentication($request);
            $state = $request->state ? Crypt::decrypt(Cache::pull($request->state)) : null;
        } else {
            $socialUser = Socialite::driver($provider)->stateless()->user();
            $state = $request->state ? Crypt::decrypt($request->state) : null;
        }


        $userData = $this->findOrCreateUser($provider, $socialUser, $state);

        $user = $userData['user'];
        $token = $userData['token'];
        if ($user && $token) {
            auth()->login($user);
        } else {
            $token = 'cannot_add';
        }

        return view('socialite/callback', [
            'token' => $token,
            'token_type' => 'bearer',
        ]);
    }


    protected function findOrCreateUser(string $provider, $user, string $state = null): array
    {
        $existingUser = null;
        $token = null;
        $email = null;

        if ($provider == 'twitter') {
            $email = $user->email;
            $providerId = $user->id;
        } else {
            $email = $user->getEmail();
            $providerId = $user->getId();
        }

        $oauthProvider = SocialiteProvider::where('provider', $provider)
            ->where('provider_user_id', $providerId)
            ->first();

        if ($state && $state != config('app.key')) {
            $token = PersonalAccessToken::findToken($state);
            if ($token) {
                $existingUser = $token->tokenable;
            }
            if ($existingUser && $existingUser->id && $oauthProvider && $oauthProvider->user_id && ($existingUser->id != $oauthProvider->user_id)) {
                return [
                    'user' => null,
                    'token' => null,
                ];
            }
        }

        if ($oauthProvider && $oauthProvider->user) {
            return [
                'user' => $oauthProvider->user,
                'token' => $oauthProvider->user->createToken($provider . '-token')->plainTextToken,
            ];
        }

        if (!$existingUser) {
            $existingUser = User::whereEmail($email)->first();
        }

        if (!$existingUser) {
            $existingUser = auth('sanctum')->user();
        }

        if ($existingUser && $oauthProvider) {
            if ($provider != 'twitter') {
                $oauthProvider->update([
                    'access_token' => $user->token ? $user->token : null,
                    'refresh_token' => $user->refreshToken ? $user->refreshToken : null,
                ]);
            }

            return [
                'user' => $oauthProvider->user,
                'token' => $oauthProvider->user->createToken($provider . '-token')->plainTextToken,
            ];
        }

        $user = $this->updateOrCreateUser($provider, $user, $existingUser);
        $token = $user->createToken($provider . '-token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    protected function updateOrCreateUser(string $provider, $sUser, $existingUser = null): User
    {
        $user = null;
        $pid = null;
        $email = null;
        $token = null;
        $refreshToken = null;
        $avatar = null;
        $emailValid = true;

        if ($existingUser) {
            $user = $existingUser;
            $email = $user->email;
            if ($provider == 'twitter') {
                $pid = $sUser->id;
                $avatar = $sUser->profile_image_url;
            } else {
                $pid = $sUser->getId();
            }
        } else {
            if ($provider == 'twitter') {
                $pid = $sUser->id;
                $email = $sUser->email;
                $name = $sUser->name;
                $avatar = $sUser->profile_image_url;
            } else {
                $pid = $sUser->getId();
                $name = $sUser->getName();
                $email = $sUser->getEmail();
                $avatar = $sUser->getAvatar();
                $token = $sUser->token;
                $refreshToken = $sUser->refreshToken;
            }

            if ($provider == 'reddit') {
                $name = $sUser->getNickname();
            }


            if (!$email) {
                $email = 'email_missing_' . Str::random(20) . '@' . Str::random(20) . '.example.org';
                $emailValid = false;
            }

            $user = User::create([
                'name' => $name,
                'username' => $name,
                'email' => $email,
                'password' => bcrypt(Str::random(50)),
            ]);


            if ($user->email && $emailValid) {
                event(new Registered($user));
                // $user->email_verified_at = Carbon::now();
            }

            $user->save();
        }

        $this->addSocialiteProviderToUser($user, [
            'provider' => $provider,
            'provider_user_id' => $pid,
            'access_token' => $token,
            'refresh_token' => $refreshToken,
            'avatar' => $avatar,
        ]);

        return $user;
    }

    protected function addSocialiteProviderToUser(User $user, $data): SocialiteProvider
    {
        $provider = SocialiteProvider::where('user_id', $user->id)
            ->where('provider', $data['provider'])
            ->where('provider_user_id', $data['provider_user_id'])->first();

        if ($provider) {
            return $provider->update([
                'access_token' => $data['access_token'],
                'refresh_token' => $data['refresh_token'],
                'avatar' => $data['avatar'],
            ]);
        }

        return $user->socialiteProviders()->create([
            'provider' => $data['provider'],
            'provider_user_id' => $data['provider_user_id'],
            'access_token' => $data['access_token'],
            'refresh_token' => $data['refresh_token'],
            'avatar' => $data['avatar'],
        ]);
    }


    public function twitterUserAuthenticationUrl($state = null)
    {
        $consumerKey = config('services.twitter.client_id');
        $consumerSecret = config('services.twitter.client_secret');
        $consumerRedirect = config('services.twitter.redirect');
        $connection = new TwitterOAuth($consumerKey, $consumerSecret);
        $tempId = $this->generateTempId();
        $requestToken = $connection->oauth('oauth/request_token', [
            'oauth_callback' => $consumerRedirect . '?state=' . $tempId,
        ]);

        $this->tempStoreStateInCache($tempId, $state);

        return $connection->url('oauth/authorize', [
            'oauth_token' => $requestToken['oauth_token'],
        ]);

    }

    public function twitterUserAuthentication(Request $request)
    {
        $consumerKey = config('services.twitter.client_id');
        $consumerSecret = config('services.twitter.client_secret');
        $consumerRedirect = config('services.twitter.redirect');

        $connection = new TwitterOAuth($consumerKey, $consumerSecret, $request->oauth_token);

        $access_token = $connection->oauth('oauth/access_token', [
            'oauth_verifier' => $request->oauth_verifier,
            'oauth_token' => $request->oauth_token,
        ]);

        $connection = new TwitterOAuth($consumerKey, $consumerSecret, $access_token['oauth_token'], $access_token['oauth_token_secret']);
        return $connection->get('account/verify_credentials', [
            'include_email' => "true",
            'skip_status' => "true",
            'include_entities' => "false",
        ]);
    }
}
