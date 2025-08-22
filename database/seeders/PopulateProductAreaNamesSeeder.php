<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PopulateProductAreaNamesSeeder extends Seeder
{
    public function run()
    {
        $affected = DB::table('product_areas')
            ->join('areas', 'product_areas.area_id', '=', 'areas.id')
            ->whereNull('product_areas.name')
            ->update(['product_areas.name' => DB::raw('areas.name')]);

        if ($this->command) {
            $this->command->info("ProductArea names populated from Area table. Updated {$affected} records.");
        } else {
            echo "ProductArea names populated from Area table. Updated {$affected} records.\n";
        }
    }
}