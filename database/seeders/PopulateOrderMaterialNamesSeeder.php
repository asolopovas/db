<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PopulateOrderMaterialNamesSeeder extends Seeder
{
    public function run()
    {
        $affected = DB::table('order_materials')
            ->join('materials', 'order_materials.material_id', '=', 'materials.id')
            ->whereNull('order_materials.name')
            ->update(['order_materials.name' => DB::raw('materials.name')]);

        if ($this->command) {
            $this->command->info("OrderMaterial names populated from Material table. Updated {$affected} records.");
        } else {
            echo "OrderMaterial names populated from Material table. Updated {$affected} records.\n";
        }
    }
}