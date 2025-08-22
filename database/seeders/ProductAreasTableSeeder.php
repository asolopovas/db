<?php

namespace Database\Seeders;

use App\Models\ProductArea;
use Illuminate\Database\Seeder;

class ProductAreasTableSeeder extends Seeder
{
    public function run()
    {
        ProductArea::factory(20)->create();
    }
}