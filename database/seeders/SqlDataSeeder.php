<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SqlDataSeeder extends Seeder
{
    public function run()
    {
        $tables = [
            'floors',
            'grades',
            'dimensions',
            'extras',
            'areas',
            'statuses',
            'materials',
            'services',
        ];

        foreach ($tables as $table) {
            DB::table($table)->truncate();
            DB::unprepared(file_get_contents(base_path()."/database/seeds/sql/{$table}.sql"));
        }
    }
}