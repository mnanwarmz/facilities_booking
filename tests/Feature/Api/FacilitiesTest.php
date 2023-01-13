<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FacilitiesTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_api_get_facilities()
    {
        // Create 10 facilities
        $facilities = \App\Models\Facility::factory()->count(10)->create();
        // Get facilities
        $response = $this->get('/api/facilities');
        // Assert return json data
        dd($response);
        $response->assertJson([
            'data' => $facilities->toArray()
        ]);
        // Assert return status code
        $response->assertStatus(200);
    }
}
