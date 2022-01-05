<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CompanyTest extends TestCase
{
    /**
     * Test Category List
     *
     * @return void
     */
    public function testCompanyList()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'api')
            ->getJson('/api/company');

        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => true,
            ]);
    }

    public function testCompanySearch()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'api')
            ->getJson('/api/company?name=amaz&favorite=true');

        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => true,
            ]);
    }
    
}
