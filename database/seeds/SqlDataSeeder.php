<?php

use Illuminate\Database\Seeder;

class SqlDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
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
