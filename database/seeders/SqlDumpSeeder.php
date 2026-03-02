<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SqlDumpSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('groups')->exists()) {
            $this->command?->warn('groups is not empty, skip import');
            return;
        }

        $fullPath = database_path('sql/data_only.sql');

        if (!file_exists($fullPath)) {
            throw new \RuntimeException("File not found: {$fullPath}");
        }

        $sql = trim(file_get_contents($fullPath));
        if ($sql === '') {
            throw new \RuntimeException("File is empty: {$fullPath}");
        }

        DB::unprepared('SET FOREIGN_KEY_CHECKS=0;');
        DB::unprepared($sql);
        DB::unprepared('SET FOREIGN_KEY_CHECKS=1;');
    }
}
