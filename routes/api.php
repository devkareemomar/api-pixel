<?php

use App\Http\Controllers\Auth\PasswordUpdateController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\GiftController;
use App\Http\Controllers\GoldPriceController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\VisitController;
use App\Http\Controllers\WidgetController;
use App\Http\Controllers\SectionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\InitRequestController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PageBuilderController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\FormController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('forgot/email', [RegistrationController::class, 'sendResetLinkEmail']);
Route::post('password/reset', [RegistrationController::class, 'reset'])->name('password.reset');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('setDataForm/{form_id}', [FormController::class, 'setDataForm']);
Route::get('getFormStatus/{order_number}', [FormController::class, 'getFormStatus']);
Route::get('getDataForm/{form_id}', [FormController::class, 'getDataForm']);



Route::get('settings', [SettingController::class, 'index']);
Route::post('/oauth/{driver}', [SocialiteController::class, 'getSocialRedirect']);
Route::get('/oauth/{driver}/callback', [SocialiteController::class, 'handleSocialCallback'])->name('oauth.callback');
Route::get('/pages/{key}', [PageController::class, 'show'])->name('pages.show');

Route::get('/general', GeneralController::class);
Route::get('settings', [SettingController::class, 'index']);
Route::post('/oauth/{driver}', [SocialiteController::class, 'getSocialRedirect']);
Route::get('/oauth/{driver}/callback', [SocialiteController::class, 'handleSocialCallback'])->name('oauth.callback');
Route::get('/pages/{id}', [PageController::class, 'show'])->name('pages.show');


Route::middleware('auth:sanctum')->group(function () {
    Route::get('profile', [ProfileController::class, 'show']);
    Route::post('profile', [ProfileController::class, 'update']);
    Route::put('password', PasswordUpdateController::class);
    Route::get('/donations', [DonationController::class, 'index'])->name('donations.index');
    Route::post('logout', [RegistrationController::class, 'logout']);
});

Route::post('login', [RegistrationController::class, 'login']);
Route::post('login/{socialType}', [RegistrationController::class, 'loginSocial']);

Route::post('signup', [RegistrationController::class, 'signup']);
Route::post('signup/{socialType}', [RegistrationController::class, 'signupSocial']);


Route::get('project', [ProjectController::class, 'project']);
Route::get('projects/all', [ProjectController::class, 'allProjects']);
Route::get('projects/banners', [ProjectController::class, 'bannerProjects']);
Route::get('projects/stories', [ProjectController::class, 'stories']);
Route::get('projects/gifts', [ProjectController::class, 'giftsProjects']);
Route::get('projects/menu', [ProjectController::class, 'menuProjects']);
Route::get('projects/continuous', [ProjectController::class, 'continuousProjects']);

Route::get('project/{id}', [ProjectController::class, 'project_details'])->name('projects.show');

Route::get('campaign', [CampaignController::class, 'campaign']);
Route::get('campaign/{campaign_id}', [CampaignController::class, 'campaign_details']);

Route::get('news', [NewsController::class, 'news']);
Route::get('news/{id}', [NewsController::class, 'news_details']);

Route::get('init/request', [InitRequestController::class, 'init']);

Route::get('link/{code}', [LinkController::class, 'link']);


Route::get('carts/{id}', [CartController::class, 'projects']);
Route::post('carts', [CartController::class, 'add']);
Route::post('carts/remove', [CartController::class, 'remove']);


Route::get('menus', [MenuController::class, 'index']);
Route::get('menus/{id}', [MenuController::class, 'show']);

Route::post('contacts', [ContactController::class, 'store']);

Route::prefix('page-builder')->group(function () {
    Route::get('/', [PageBuilderController::class, 'index']);

    Route::post('sections', [SectionController::class, 'store']);
    Route::put('sections/{section}', [SectionController::class, 'update']);
    Route::delete('sections/{section}', [SectionController::class, 'destroy']);

    Route::post('widgets', [WidgetController::class, 'store']);
    Route::delete('widgets/{widget}', [WidgetController::class, 'destroy']);

    Route::apiResource('widget-categories', SliderController::class);
});

Route::apiResource('sliders', SliderController::class);


Route::post('payment/create', [PaymentController::class, 'create']);
Route::post('payment/check', [PaymentController::class, 'check']);
Route::get('payment/verify', [PaymentController::class, 'callbackVerify'])->name('payment.verify');
Route::get('myfatoorah/callback', [PaymentController::class, 'callback'])->name('payment.myfatoorah.callback');

Route::get('payment', [PaymentController::class, 'index']);

Route::post('visit', [VisitController::class, 'visit']);

Route::get('gifts', [GiftController::class, 'index']);
Route::get('gifts/{id}', [GiftController::class, 'show']);
Route::post('gifts', [GiftController::class, 'store']);

Route::get('search', [ProjectController::class, 'search']);
Route::get('gold-prices', GoldPriceController::class);
