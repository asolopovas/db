<?php

namespace App\Providers;

use App\Observers\OrderLastPaymentDateUpdate;
use App\Models\ProductArea;
use App\Observers\UpdateOrderSums;
use App\Observers\ProductMeterageUpdate;
use App\Models\OrderMaterial;
use App\Models\Customer;
use App\Models\OrderService;
use App\Models\Payment;
use App\Models\Product;
use App\Models\TaxDeduction;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::component('components.table', 'table');
        Blade::directive('currency', function ($value) {
            return "<?php echo '£' . number_format($value, 2); ?>";
        });

        Blade::directive('combo', function ($args) {
            return "<?php echo trim(implode(' ', $args)); ?>";
        });

        Blade::directive('date', function ($expression) {
            return "<?php echo ($expression)->format('d M Y'); ?>";
        });

        ProductArea::observe(ProductMeterageUpdate::class);
        Payment::observe(UpdateOrderSums::class);
        TaxDeduction::observe(UpdateOrderSums::class);
        Payment::observe(OrderLastPaymentDateUpdate::class);
        Product::observe(UpdateOrderSums::class);
        OrderMaterial::observe(UpdateOrderSums::class);
        OrderService::observe(UpdateOrderSums::class);

        // if (app()->environment('local')) {
        //     config(['view.compiled' => false]);
        // }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        if ($this->app->environment('local')) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }

    }
}
