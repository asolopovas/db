<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PopulateOrderIdsSeeder extends Seeder
{
    public function run(): void
    {
        $affected = DB::table('orders')
            ->update(['base_order_id' => DB::raw('id')]);

        if ($this->command) {
            $this->command->info("Base order ids  populated. Updated {$affected} records.");
        } else {
            echo "Base order ids populated. Updated {$affected} records.\n";
        }
    }
}
