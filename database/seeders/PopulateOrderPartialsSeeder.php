<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PopulateOrderPartialsSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            PopulateOrderServiceNamesSeeder::class,
            PopulateOrderMaterialNamesSeeder::class,
            PopulateProductAreaNamesSeeder::class
        ]);
    }
}
