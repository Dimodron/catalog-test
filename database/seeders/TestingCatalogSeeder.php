<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestingCatalogSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('groups')->insert([
            ['id' => 1, 'id_parent' => 0, 'name' => 'Group A'],
            ['id' => 2, 'id_parent' => 1, 'name' => 'Group B'],
        ]);

        DB::table('products')->insert([
            ['id' => 1, 'id_group' => 2, 'name' => 'Product A'],
            ['id' => 2, 'id_group' => 2, 'name' => 'Product B'],
        ]);

        DB::table('prices')->insert([
            ['id' => 1, 'id_product' => 1, 'price' => 100],
            ['id' => 2, 'id_product' => 2, 'price' => 200],
        ]);
    }
}
