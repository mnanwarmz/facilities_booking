<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_generate_token()
    {
        $this->withoutExceptionHandling();
        // Create user
        $user = \App\Models\User::factory()->create([
            'password' => bcrypt('password')
        ]);
        // Login user
        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);
        // Assert return json data
        $response->assertJsonStructure([
            'access_token',
            'token_type',
        ]);
        // Assert return status code
        $response->assertStatus(200);
    }

    public function test_can_revoke_token()
    {
        $this->withoutExceptionHandling();
        // Create user
        $user = \App\Models\User::factory()->create([
            'password' => bcrypt('password')
        ]);
        // Login user
        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);
        $response->assertJsonStructure([
            'access_token',
            'token_type',
        ]);
        // Revoke token
        $response = $this->post('/api/logout');
        // Assert return json data
        $response->assertJsonStructure([
            'message',
        ]);
        // assert user has no token
        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $user->id
        ]);
    }

    public function test_user_with_token_can_access_guarded_endpoints()
    {
        $this->withoutExceptionHandling();
        // Create user
        $user = \App\Models\User::factory()->create([
            'password' => bcrypt('password')
        ]);
        // Login user
        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);
        $response->assertJsonStructure([
            'access_token',
            'token_type',
        ]);
        // Get user
        $response = $this->get('/api/user');
        $response->assertStatus(200);
    }
}
