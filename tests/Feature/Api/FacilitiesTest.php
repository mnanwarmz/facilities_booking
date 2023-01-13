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
        $this->withoutExceptionHandling();
        // Login
        $user = \App\Models\User::factory()->create([
            'password' => bcrypt('password')
        ]);
        // Login user
        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);
        $this->assertAuthenticated();
        // Create 10 facilities
        $facilities = \App\Models\Facility::factory()->count(10)->create();
        // Get facilities
        $response = $this->get('/api/facilities');
        // Assert status code
        $response->assertStatus(200);

        // Assert return json data
        $response->assertJson([
            'data' => $facilities->toArray()
        ]);
    }
}
