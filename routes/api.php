<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\SettingsAPIController;

use App\Http\Controllers\API\Driver\DriverAPIController;
use \App\Http\Controllers\API\RideAPIController;
use \App\Http\Controllers\API\Driver\DriverRideAPIController;
use \App\Http\Controllers\API\UserAPIController;
use \App\Http\Controllers\API\WebhookAPIController;
use \App\Http\Middleware\DriverAuth;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::any('webhook/stripe', 'WebhookAPIController@stripeWebhook');
Route::any('webhook/paypal', 'WebhookAPIController@paypalWebhook');
Route::any('webhook/mercadopago', 'WebhookAPIController@mercadoPagoWebhook');
Route::any('webhook/flutterwave', 'WebhookAPIController@flutterwaveWebhook');
Route::any('webhook/razorpay', 'WebhookAPIController@razorpayWebhook');

Route::get('drivers', [DriverAPIController::class, 'index']);

Route::get('settings', [SettingsAPIController::class, 'settings']);

Route::group(['prefix' => 'driver'], function () {
    Route::post('login', [DriverAPIController::class, 'login']);
    Route::post('register', [DriverAPIController::class, 'register']);
});

Route::post('login', [UserAPIController::class, 'login']);
Route::post('register', [UserAPIController::class, 'register']);

Route::post('login/check', [UserAPIController::class, 'loginCheck']);

Route::post('forgot-password', [UserAPIController::class, 'forgotPassword']);

Route::middleware('auth:api')->group(function () {

    Route::middleware(DriverAuth::class)->prefix('driver')->group(function () {

        Route::get('rides/values', [DriverRideAPIController::class, 'values']);
       
        Route::resource('rides', DriverRideAPIController::class)->only([
            'index', 'show'
        ]);

        Route::post('updateRegister', [DriverAPIController::class, 'updateRegister']);

        Route::get('login/verify', [DriverAPIController::class, 'verifyLogin']);
        Route::get('checkNewRide', [DriverRideAPIController::class, 'checkNewRide']);
        Route::patch('updateStatus', [DriverRideAPIController::class, 'updateStatus']);
        Route::get('getSummarizedBalance', [DriverRideAPIController::class, 'getSummarizedBalance']);
    });

    //general routes that can be used by any role
    Route::get('login/verify', [UserAPIController::class, 'verifyLogin']);
    Route::post('profile', [UserAPIController::class, 'updateProfile']);
    Route::post('profile/picture', [UserAPIController::class, 'updateProfilePicture']);
    Route::delete('delete-account', [UserAPIController::class, 'deleteAccount']);


    Route::get('active', [DriverAPIController::class, 'getRideActive']);
    Route::patch('active', [DriverAPIController::class, 'updateRideActive']);
    Route::patch('settings', [DriverAPIController::class, 'updateSettings']);

    Route::patch('location', [DriverAPIController::class, 'updateLocation']);

    Route::post('driver/findNearBy', [DriverAPIController::class, 'findNearBy']);


    Route::post('rides/simulate', [RideAPIController::class, 'simulate']);
    Route::resource('rides', RideAPIController::class)->only([
        'index', 'show', 'store'
    ]);
    Route::post('rides/cancel', [RideAPIController::class, 'cancel']);
    Route::post('rides/getDriverPosition', [RideAPIController::class, 'getDriverPosition']);

    Route::get('rides/{id}/checkPaymentByRideID', [RideAPIController::class, 'checkPaymentByRideID']);
    Route::get('rides/{id}/status', [RideAPIController::class, 'checkStatus']);
    Route::get('rides/{id}/payWithMercadoPago', [RideAPIController::class, 'payWithMercadoPago']);
    Route::get('rides/{id}/payWithPayPal', [RideAPIController::class, 'payWithPayPal']);
    Route::get('rides/{id}/payWithFlutterwave',[RideAPIController::class,'payWithFlutterwave']);
    Route::get('rides/{id}/payWithRazorpay',[RideAPIController::class,'payWithRazorpay']);
    Route::post('rides/{id}/success', [RideAPIController::class,'paymentSuccessScreen'])->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

    Route::post('rides/initializePayment', [RideAPIController::class, 'initializePayment']);

    Route::post('notifications/update_token', [UserAPIController::class, 'updateToken']);

    Route::resource('messages', MessageAPIController::class)->only([
        'index', 'store'
    ]);
});
