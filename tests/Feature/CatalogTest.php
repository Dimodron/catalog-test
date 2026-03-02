<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class CatalogTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\TestingCatalogSeeder::class);
    }

    public function test_main_page_loads()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Все товары');
    }

    public function test_sorting_by_price_desc()
    {
        $response = $this->get('/?sort=price&dir=desc');

        $response->assertStatus(200);
    }

    public function test_group_page_loads()
    {
        $groupId = DB::table('groups')->min('id');
        $this->assertNotNull($groupId);

        $this->get("/group/{$groupId}")
            ->assertStatus(200);
    }
}
