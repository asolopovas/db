<?php

use Illuminate\Database\Seeder;

class OrderServicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory('App\OrderService', 20)->create();
    }
}
