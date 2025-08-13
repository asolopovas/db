<?php

use Illuminate\Database\Seeder;

class OrderMaterialsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory('App\OrderMaterial', 20)->create();
    }
}
