<?php

use App\Http\Controllers\UsersController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\OrderServicesController;
use App\Http\Controllers\OrderMaterialsController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\FloorsController;
use App\Http\Controllers\GradesController;
use App\Http\Controllers\ExtrasController;
use App\Http\Controllers\DimensionsController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\ProductAreasController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\StatusesController;
use App\Http\Controllers\MaterialsController;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\TaxDeductionsController;
use App\Http\Controllers\ExpensesController;

Route::middleware(['can:is_admin,App\Models\User'])->group(function () {
    Route::resource('users', UsersController::class);
    Route::patch('users/{id}', [UsersController::class, 'update']);
    Route::resource('roles', RolesController::class);
    Route::get('stats', [OrdersController::class, 'stats']);
    Route::get('products-data', [ProductsController::class, 'exportProductsData']);
    Route::get('services-data', [OrderServicesController::class, 'exportServicesData']);
    Route::get('materials-data', [OrderMaterialsController::class, 'exportMaterialsData']);
    Route::get('tax-deducted-orders', [OrdersController::class, 'exportTaxDeductedOrders']);
    Route::get('commissions-data', [OrdersController::class, 'exportCommissionsData']);
});

Route::get('orders/totals', [OrdersController::class, 'totals']);
Route::get('orders/notes-view', [OrdersController::class, 'notesView']);
Route::get('orders/{order}/related', [OrdersController::class, 'relatedIds']);
Route::post('orders/{id}/request-{item}', [OrdersController::class, 'sendRequest']);
Route::get('orders/{id}/pdf-default', [OrdersController::class, 'viewPdf']);
Route::get('orders/{id}/pdf-reverse-charge', [OrdersController::class, 'reverseChargePdf']);
Route::get('orders/{id}/default-html', [OrdersController::class, 'viewDefaultHtml']);
Route::get('orders/{id}/footer-html', [OrdersController::class, 'footerHtml']);
Route::get('orders/{id}/reverse-charge-html', [OrdersController::class, 'viewReverseChargeHtml']);
Route::get('orders/{id}/download', [OrdersController::class, 'download']);
Route::post('orders/{id}/send', [OrdersController::class, 'send']);
Route::post('orders/{id}/duplicate', [OrdersController::class, 'duplicate']);
Route::resource('orders', OrdersController::class);

Route::get('attachments', [SettingsController::class, 'orderAttachments']);
Route::post('attachments', [SettingsController::class, 'uploadAttachments']);
Route::delete('attachments', [SettingsController::class, 'deleteAttachments']);

Route::resource('areas', AreaController::class);
Route::resource('companies', CompaniesController::class);
Route::resource('customers', CustomersController::class);
Route::resource('dimensions', DimensionsController::class);
Route::resource('expenses', ExpensesController::class);
Route::resource('extras', ExtrasController::class);
Route::resource('floors', FloorsController::class);
Route::resource('grades', GradesController::class);
Route::resource('materials', MaterialsController::class);
Route::resource('order_materials', OrderMaterialsController::class);
Route::resource('order_services', OrderServicesController::class);
Route::resource('payments', PaymentsController::class);
Route::resource('product_areas', ProductAreasController::class);
Route::resource('products', ProductsController::class);
Route::post('products/{id}/updateMeterage', [ProductsController::class, 'updateMeterage']);
Route::resource('projects', ProjectsController::class);
Route::resource('services', ServicesController::class);
Route::resource('settings', SettingsController::class);
Route::resource('statuses', StatusesController::class);
Route::resource('tax_deductions', TaxDeductionsController::class);

Route::prefix('settings-query')->group(function () {
    Route::post('save', [SettingsController::class, 'setSettings']);
    Route::get('get/all', [SettingsController::class, 'getSettings']);
    Route::get('get/{key}', [SettingsController::class, 'getSetting']);
});

Route::get('customers/{id}/orders', [CustomersController::class, 'orders']);
Route::get('floors/prev/{id}', [FloorsController::class, 'previous']);
Route::get('floors/next/{id}', [FloorsController::class, 'next']);


Route::get('user', function () {
    return auth()->user()->load('role');
});
