<?php

namespace Database\Seeders;

use App\Models\OrderService;
use Illuminate\Database\Seeder;

class OrderServicesTableSeeder extends Seeder
{
    public function run()
    {
        OrderService::factory(20)->create();
    }
}