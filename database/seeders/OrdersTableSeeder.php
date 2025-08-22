<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderMaterial;
use App\Models\OrderService;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductArea;
use Illuminate\Database\Seeder;

class OrdersTableSeeder extends Seeder
{
    public function run()
    {
        Order::factory(1)->create()->each(function($order) {

            OrderMaterial::factory(3)->create(['order_id' => $order->id]);
            OrderService::factory(3)->create(['order_id' => $order->id]);
            Payment::factory(2)->create(['order_id' => $order->id]);
            $random_number = rand(1, 2);
            if ($random_number === 1) {
                Product::factory(2)
                    ->create(['order_id' => $order->id])
                    ->each(function($product) use ($order) {
                        ProductArea::factory(2)->create([
                            'order_id'   => $order->id,
                            'product_id' => $product->id,
                        ]);
                    });
            } else {
                Product::factory(2)->create(['order_id' => $order->id]);
            }
        });
    }
}