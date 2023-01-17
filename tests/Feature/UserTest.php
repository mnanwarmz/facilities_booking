<?php

namespace Tests\Feature;

use Database\Seeders\InitialSetupSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    // Setup
    public function setUp(): void
    {
        parent::setUp();
        $this->seed(InitialSetupSeeder::class);
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_admin_can_view_all_users()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($user = \App\Models\User::factory()->create());
        $user->assignRole('admin');
        $response = $this->get('/api/users');
        $response->assertStatus(200);
    }
}
