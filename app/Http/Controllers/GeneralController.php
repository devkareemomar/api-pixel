<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartProjectResource;
use App\Http\Resources\FormResource;
use App\Http\Resources\LanguageResource;
use App\Http\Resources\SitePageResource;
use App\Models\Cart;
use App\Models\Currency;
use App\Models\FormBuilder;
use App\Models\Language;
use App\Models\Menu;
use App\Models\SessionData;
use App\Models\Setting;
use App\Models\SitePage;
use App\Models\User;
use Illuminate\Http\Request;
use stdClass;

class GeneralController extends Controller
{
    public function __invoke(Request $request)
    {
        $page = SitePage::where([
            'key' => 'home',
            'lang' => config('app.locale'),
        ])->firstOrFail();

        $dto = new stdClass();
        $dto->homePage = $page;

        $languages = Language::select('id', 'name', 'short_name', 'is_default', 'flag')->get();
        $languages = LanguageResource::collection($languages);
        $currencies = Currency::select('id', 'name', 'code', 'is_default', 'exchange_rate')->get();
        $settings = Setting::select('use_captcha_on_registration', 'use_captcha_on_login', 'meta_tags_head',
            'meta_tags_body', 'meta_tags_footer')->first();
        $form_data = FormResource::collection(FormBuilder::where('active', 1)->get());

        $cart = [];
        $cartItems = [];
        $projects = null;
        $userId = auth()->user()?->id;
        if ($userId) {
            $cart = Cart::where('user_id', auth()->id())->latest()->first();
        } else {
            if ($request->input('user_session')) {
                $cart = Cart::where('session_id', $request->input('user_session'))->latest()->first();
            }
        }

        if ($cart) {
            $projects = $cart->cartProjects()->with('project')->get();
        }


        $mainMenu = $this->getMainMenu();
        $footerMenu = $this->getFooterMenu();

        return response()->json([
            'data' => [
                'user_data' => self::user_data($request),
                'menu' => $mainMenu,
                'footer_menu' => $footerMenu,
                'languages' => $languages,
                'settings' => $settings,
                'currencies' => $currencies,
                'form_data' => $form_data,
                'homePage' => SitePageResource::make($dto->homePage),
                'cart' => $projects ? CartProjectResource::collection($projects) : [],
            ],
        ]);

    }

    protected function user_data($request)
    {
        if ($request['auth'] != null) {
            $user_data = User::select('users.name', 'email', 'username',
                'languages.name as language_name', 'languages.short_name as language_short_name',
                'languages.flag as language_flag',
                'currencies.name as currency_name', 'currencies.code as currency_code')
                ->where('users.id', $request['auth']['id'])
                ->leftJoin('languages', 'languages.id', '=', 'users.language_id')
                ->leftJoin('currencies', 'currencies.id', '=', 'users.currency_id')
                ->first();
        } else {
            $session_id = $request['session_id'];
            $user_data = SessionData::select('session_id', 'languages.name as language_name',
                'languages.short_name as language_short_name', 'languages.flag as language_flag',
                'currencies.name as currency_name', 'currencies.code as currency_code')
                ->where('session_data.session_id', $session_id)
                ->leftJoin('languages', 'languages.id', '=', 'session_data.language_id')
                ->leftJoin('currencies', 'currencies.id', '=', 'session_data.currency_id')
                ->first();
        }
        return $user_data;
    }

    public function getMainMenu()
    {
        $menu = Menu::query()
            ->where('position', 'header')
            ->where('locale', config('app.locale'))
            ->first();

        if ($menu) {
            $menu->items->each(function ($item) {
                $this->loadSubsRecursively($item);
                $childrenImages = $item->childrenImages();
                $childrenImages ? ($item->childrenImages = $childrenImages) : null;
            });
            return $menu;
        }

        return null;
    }

    public function getFooterMenu()
    {
        $menu = Menu::query()
            ->where('position', 'footer')
            ->where('locale', config('app.locale'))
            ->first();
        if ($menu) {
            $menu->items->each(function ($item) {
                $this->loadSubsRecursively($item);
                $childrenImages = $item->childrenImages();
                $childrenImages ? ($item->childrenImages = $childrenImages) : null;
            });
            return $menu;
        }

        return null;
    }

    public function loadSubsRecursively($item)
    {
        $subs = $item->subs()->get();

        if ($subs->count() > 0) {
            $subs->each(function ($sub) {
                $this->loadSubsRecursively($sub);
            });
        }

        $item->subs = $subs;
    }
}
