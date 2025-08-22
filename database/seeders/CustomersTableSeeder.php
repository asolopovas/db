<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderMaterial;
use App\Models\OrderService;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductArea;
use App\Models\Area;
use Illuminate\Database\Seeder;

class CustomersTableSeeder extends Seeder
{
    public function run()
    {
        Customer::factory(1)->create()->each(function ($customer) {
            Order::factory(2)
        ->create(['customer_id' => $customer->id])
        ->each(function ($order) {
            OrderMaterial::factory(3)->create(['order_id' => $order->id]);
            OrderService::factory(3)->create(['order_id' => $order->id]);
            Payment::factory(2)->create(['order_id' => $order->id]);

            $random_number = rand(1, 2);
            if ($random_number === 1) {
                Product::factory(2)
              ->create(['order_id' => $order->id])
              ->each(function ($product) use ($order) {
                  ProductArea::factory(2)->create([
                  'product_id' => $product->id,
                  'area_id'    => Area::all()->random()->id,
                ]);
              });
            } else {
                Product::factory(2)->create(['order_id' => $order->id]);
            }
        });
        });
    }
}