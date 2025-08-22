<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    Model::unguard();
    $this->setFKCheckOff();

    $seeders = [
      'areas',
      'companies',
      'customers',
      'dimensions',
      'extras',
      'floors',
      'product_areas',
      'grades',
      'materials',
      'migrations',
      'orders',
      'order_materials',
      'order_services',
      'password_resets',
      'payments',
      'products',
      'projects',
      'roles',
      'services',
      'settings',
      'statuses',
      'users',
    ];

    foreach ($seeders as $seeder) :
      DB::table($seeder)->truncate();
    endforeach;
    // Seed the tables
    $this->call(UsersTableSeeder::class);
    $this->call(RolesTableSeeder::class);
    $this->call(SqlDataSeeder::class);
    $this->call(SettingsDataSeeder::class);
    $this->call(CompaniesTableSeeder::class);
    if (env('APP_ENV') === 'local') {
      $this->call(CustomersTableSeeder::class);
      $this->call(ProjectsTableSeeder::class);
    }

    $this->call(PopulateOrderMaterialNamesSeeder::class);

    $this->setFKCheckOn();
    Model::reguard();
  }

  private function setFKCheckOff()
  {
    switch (DB::getDriverName()) {
      case 'mysql':
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        break;
      case 'sqlite':
        DB::statement('PRAGMA foreign_keys = OFF');
        break;
    }
  }

  private function setFKCheckOn()
  {
    switch (DB::getDriverName()) {
      case 'mysql':
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        break;
      case 'sqlite':
        DB::statement('PRAGMA foreign_keys = ON');
        break;
    }
  }
}
