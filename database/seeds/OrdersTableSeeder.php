<?php

use Illuminate\Database\Seeder;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory('App\Order', 1)->create()->each(function($order) {

            factory(App\OrderMaterial::class, 3)->create(['order_id' => $order->id]);
            factory(App\OrderService::class, 3)->create(['order_id' => $order->id]);
            factory(App\Payment::class, 2)->create(['order_id' => $order->id]);
            $random_number = rand(1, 2);
            if ($random_number === 1) {
                factory(App\Product::class, 2)
                    ->create(['order_id' => $order->id])
                    ->each(function($product) use ($order) {
                        factory(App\ProductArea::class, 2)->create([
                            'order_id'   => $order->id,
                            'product_id' => $product->id,
                        ]);
                    });
            } else {
                factory(App\Product::class, 2)->create(['order_id' => $order->id]);
            }
        });
    }
}
