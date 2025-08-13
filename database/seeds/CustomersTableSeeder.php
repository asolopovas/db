<?php

use Illuminate\Database\Seeder;

class CustomersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory('App\Customer', 1)->create()->each(function ($customer) {
            factory(App\Order::class, 2)
        ->create(['customer_id' => $customer->id])
        ->each(function ($order) {
            factory(App\OrderMaterial::class, 3)->create(['order_id' => $order->id]);
            factory(App\OrderService::class, 3)->create(['order_id' => $order->id]);
            factory(App\Payment::class, 2)->create(['order_id' => $order->id]);

            $random_number = rand(1, 2);
            if ($random_number === 1) {
                factory(App\Product::class, 2)
              ->create(['order_id' => $order->id])
              ->each(function ($product) use ($order) {
                  factory(App\ProductArea::class, 2)->create([
                  'product_id' => $product->id,
                  'area_id'    => App\Area::all()->random()->id,
                ]);
              });
            } else {
                factory(App\Product::class, 2)->create(['order_id' => $order->id]);
            }
        });
        });
    }
}
