<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PopulateOrderIdsSeeder extends Seeder
{
    public function run(): void
    {
        // Populate missing order_id values with the current numeric id
        DB::statement("UPDATE orders SET order_id = CAST(id AS CHAR) WHERE order_id IS NULL OR order_id = ''");
    }
}

