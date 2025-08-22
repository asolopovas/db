<?php

namespace Database\Seeders;

use App\Models\OrderMaterial;
use Illuminate\Database\Seeder;

class OrderMaterialsTableSeeder extends Seeder
{
    public function run()
    {
        OrderMaterial::factory(20)->create();
    }
}