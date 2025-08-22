<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('projects')->insert([
            'street'     => '71 Cowen Avenue',
            'town'       => 'Harrow',
            'county'     => 'Middlesex',
            'city'       => 'London',
            'postcode'   => 'HA20LU',
            'country'    => 'GB',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}