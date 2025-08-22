<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            'name'       => env('ADMIN_NAME', 'Andrius Solopovas'),
            'username'   => env('ADMIN_USERNAME', 'asolopovas'),
            'email'      => env('ADMIN_EMAIL', 'andrius.solopovas@gmail.com'),
            'phone'      => '0208 840 8031',
            'mobile'     => '0750 228 3720',
            'role_id'    => 1,
            'password'   => bcrypt(env('ADMIN_PASSWORD', 'secret')),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('users')->insert([
            'name'       => "Ben Foster",
            'username'   => 'bfoster',
            'phone'      => '0208 840 8031',
            'mobile'     => '0754 049 2060',
            'email'      => 'info@3oak.co.uk',
            'role_id'    => 2,
            'password'   => bcrypt('2hgPi#H5TUNy35qM'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}