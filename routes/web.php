<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\XeroController;
use App\Models\Order;
use App\Models\XeroToken;
use App\Mail\OrderMail;
use Illuminate\Support\Carbon;

Route::get('xero-cache', function () {
    $accessTokenKey = 'xero_access_token';
    $tokenRecord = Cache::rememberForever('xero_token', function () {
        return XeroToken::firstOrFail();
    });
    $currentTimestamp = now()->getTimestamp();
    $tokenExpiry = Cache::get($accessTokenKey . '_expires');
    $access_token = Cache::get('xero_access_token') ? 'Present' : 'N/A';
    $token_record_created = $tokenRecord ? Carbon::parse($tokenRecord->created_at)->format('d-M-Y H:i:s') : 'N/A';

    return [
        'token_record_created' => $token_record_created,
        'refresh_token_expires' => $tokenRecord ? Carbon::parse($tokenRecord->reauthenticate_at)->format('d-M-Y H:i:s')  : 'N/A',
        'access_token_expires' => Carbon::parse($tokenExpiry)->format('d-M-Y H:i:s'),
        'access_token_expiry_seconds' =>  $tokenExpiry - $currentTimestamp,
        'access_token' => $access_token,
        'expired' => !$tokenExpiry || $currentTimestamp > $tokenExpiry,
    ];
});
if (app()->isLocal()) {

    Route::get('xero-customers', [XeroController::class, 'getCustomers']);
    Route::get('xero-customer/{xero_contact_id}', [XeroController::class, 'getCustomer']);
    Route::get('xero-create-invoice', [XeroController::class, 'createInvoice']);

    Route::get('user', function () {
        return auth()->user()->load('role');
    });

    Route::get('/mail/{id}', function ($id) {
        $order = Order::where('id', $id)->first();
        return new OrderMail($order);
    });

    Route::get('/products/{product}', [ProductsController::class, 'show']);
    Route::get('stats', [OrdersController::class, 'stats']);
    Route::get('orders/{order}', [OrdersController::class, 'show']);
    Route::get('orders/{id}/html', [OrdersController::class, 'viewDefaultHtml']);
    Route::get('orders/{id}/html-footer', [OrdersController::class, 'footerHtml']);
    Route::get('orders/{id}/pdf', [OrdersController::class, 'pdf']);
    Route::get('{id}/send', [OrdersController::class, 'send']);
}

Route::get('xero-redirect-auth', [XeroController::class, 'handleRedirectAuth']);

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
});

Route::view('/{any}', 'app')->where('any', '.*');
