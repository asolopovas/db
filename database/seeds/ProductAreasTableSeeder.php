<?php

use Illuminate\Database\Seeder;

class ProductAreasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory('App\ProductArea', 20)->create();
    }
}
