<?php

namespace App\Providers;

use App\Models\Area;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Dimension;
use App\Models\Expenses;
use App\Models\Extra;
use App\Models\Floor;
use App\Models\Grade;
use App\Models\Material;
use App\Models\Order;
use App\Models\OrderMaterial;
use App\Models\OrderService;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductArea;
use App\Models\Project;
use App\Models\Role;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Status;
use App\Models\TaxDeduction;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {

        Route::model('area', Area::class);
        Route::model('company', Company::class);
        Route::model('customer', Customer::class);
        Route::model('dimension', Dimension::class);
        Route::model('expense', Expenses::class);
        Route::model('extra', Extra::class);
        Route::model('floor', Floor::class);
        Route::model('grade', Grade::class);
        Route::model('material', Material::class);
        Route::model('order', Order::class);
        Route::model('order_material', OrderMaterial::class);
        Route::model('order_service', OrderService::class);
        Route::model('payment', Payment::class);
        Route::model('product', Product::class);
        Route::model('product_area', ProductArea::class);
        Route::model('project', Project::class);
        Route::model('role', Role::class);
        Route::model('service', Service::class);
        Route::model('setting', Setting::class);
        Route::model('statuses', Status::class);
        Route::model('tax_deduction', TaxDeduction::class);
        Route::model('user', User::class);

        $this->configureRateLimiting();
        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
