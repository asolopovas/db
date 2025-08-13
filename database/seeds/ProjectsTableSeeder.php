<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ProjectsTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
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
