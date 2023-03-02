<?php

use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('login/{service}', [\App\Http\Controllers\Auth\LoginController::class, 'redirectToProvider']);
Route::get('login/{service}/callback', [\App\Http\Controllers\Auth\LoginController::class, 'handleProviderCallback']);
Route::get('firebase/gen', '\App\Http\Controllers\AppSettingsController@generateFirebase');
Route::get('firebase-messaging-sw.js', '\App\Http\Controllers\AppSettingsController@generateFirebase');

Auth::routes();

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', 'App\Http\Controllers\Admin\DashboardController@index')->name('dashboard');
    Route::post('/dashboard/ajaxGetRides', 'App\Http\Controllers\Admin\DashboardController@ajaxGetRides')->name('dashboard.ajaxGetRides');

    Route::get('customersJson', [\App\Http\Controllers\Admin\UserController::class, 'indexJson'])->name('customersJson');
    Route::get('driversJson', [\App\Http\Controllers\Admin\DriverController::class, 'indexJson'])->name('driversJson');

    Route::resource('drivers', \App\Http\Controllers\Admin\DriverController::class)->except(['create', 'store']);

    Route::get('settings/general', [\App\Http\Controllers\Admin\SettingsController::class, 'general'])->name('settings.general');
    Route::get('settings/app', [\App\Http\Controllers\Admin\SettingsController::class, 'app'])->name('settings.app');
    Route::get('settings/translations', [\App\Http\Controllers\Admin\SettingsController::class, 'translations'])->name('settings.translations');
    Route::get('settings/currencies', [\App\Http\Controllers\Admin\SettingsController::class, 'currencies'])->name('settings.currencies');
    Route::get('settings/social_login', [\App\Http\Controllers\Admin\SettingsController::class, 'social_login'])->name('settings.social_login');
    Route::get('settings/payments_api', [\App\Http\Controllers\Admin\SettingsController::class, 'payments_api'])->name('settings.payments_api');
    Route::get('settings/notifications', [\App\Http\Controllers\Admin\SettingsController::class, 'notifications'])->name('settings.notifications');
    Route::get('settings/legal', [\App\Http\Controllers\Admin\SettingsController::class, 'legal'])->name('settings.legal');
    Route::get('settings/currency', [\App\Http\Controllers\Admin\SettingsController::class, 'currency'])->name('settings.currency');
    Route::get('settings/clear-cache', [\App\Http\Controllers\Admin\SettingsController::class, 'clearCache'])->name('settings.clear_cache');
    Route::resource('settings/currencies', App\Http\Controllers\Admin\CurrencyController::class);
    Route::resource('settings/roles', \App\Http\Controllers\Admin\RoleController::class);
    Route::resource('settings/offlinePaymentMethods', \App\Http\Controllers\Admin\OfflinePaymentMethodController::class);
    Route::get('settings/permissions', [\App\Http\Controllers\Admin\PermissionController::class, 'index'])->name('permissions.index');
    Route::post('settings/permissions/update', [\App\Http\Controllers\Admin\PermissionController::class, 'update'])->name('permissions.update');
    Route::patch('settings/saveSettings', [\App\Http\Controllers\Admin\SettingsController::class, 'storeSettings'])->name('settings.saveSettings');




    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::get('users/login_as/{id}', [App\Http\Controllers\Admin\UserController::class, 'loginAs'])->name('users.login_as');

    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);


    Route::get('rides/ajaxGetAddressesHtml', [\App\Http\Controllers\Admin\RideController::class, 'ajaxGetAddressesHtml'])->name('rides.ajaxGetAddressesHtml');
    Route::resource('rides', \App\Http\Controllers\Admin\RideController::class)->only(['index', 'show', 'edit', 'update', 'destroy']);


    Route::get('driverPayouts/driverTable', [\App\Http\Controllers\Admin\DriverPayoutController::class, 'getDriverPayoutDataTable'])->name('driverPayouts.driverTable');
    Route::get('driverPayouts/driverSummary', [\App\Http\Controllers\Admin\DriverPayoutController::class, 'getDriverPayoutSummaryDataTable'])->name('driverPayouts.driverSummary');
    Route::resource('driverPayouts', \App\Http\Controllers\Admin\DriverPayoutController::class);


    Route::any('reports/ridesByDate', [\App\Http\Controllers\Admin\ReportController::class, 'ridesByDate'])->name('reports.ridesByDate');
    Route::any('reports/ridesByDriver', [\App\Http\Controllers\Admin\ReportController::class, 'ridesByDriver'])->name('reports.ridesByDriver');
    Route::any('reports/ridesByCustomer', [\App\Http\Controllers\Admin\ReportController::class, 'ridesByCustomer'])->name('reports.ridesByCustomer');


    Route::resource('vehicle_types', \App\Http\Controllers\Admin\VehicleTypeController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/rides', [\App\Http\Controllers\RideController::class, 'index'])->name('rides.index');
    Route::get('/rides/ajaxGetAddressesHtml', [\App\Http\Controllers\RideController::class, 'ajaxGetAddressesHtml'])->name('rides.ajaxGetAddressesHtml');
    Route::get('/rides/{ride}', [\App\Http\Controllers\RideController::class, 'show'])->name('rides.show');
    Route::post('/rides/{ride}', [\App\Http\Controllers\RideController::class,'show'])->name('orders.show')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
});

Route::get('install/purchase', function (){
    return view('vendor/installer/purchase');
})->name('install.purchase');

Route::get('logout', function () {
    auth()->logout();
    return redirect('/');
});

Route::get('terms', function () {
    return view('auth.terms');
})->name('terms');
Route::get('privacy', function () {
    return view('auth.privacy');
})->name('privacy');

//Route::get('{slug}', [\App\Http\Controllers\DriverController::class, 'index'])->name('slug');
