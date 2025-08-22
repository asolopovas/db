<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PopulateOrderServiceNamesSeeder extends Seeder
{
    public function run()
    {
        $affected = DB::table('order_services')
            ->join('services', 'order_services.service_id', '=', 'services.id')
            ->whereNull('order_services.name')
            ->update(['order_services.name' => DB::raw('services.name')]);

        if ($this->command) {
            $this->command->info("OrderService names populated from Service table. Updated {$affected} records.");
        } else {
            echo "OrderMaterial names populated from Material table. Updated {$affected} records.\n";
        }
    }
}
