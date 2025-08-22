<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductArea;

class PopulateProductAreaNamesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $productAreas = ProductArea::whereNull('name')
            ->orWhere('name', '')
            ->with('area')
            ->chunk(100, function ($productAreas) {
                foreach ($productAreas as $productArea) {
                    if ($productArea->area) {
                        $productArea->update([
                            'name' => $productArea->area->name
                        ]);
                    }
                }
            });

        $this->command->info('ProductArea names populated from Area table.');
    }
}