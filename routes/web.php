<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ProductsController;
use App\Models\Order;
use App\Mail\OrderMail;
use Illuminate\Support\Carbon;

if (app()->isLocal()) {

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
