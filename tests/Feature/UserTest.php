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

    public function test_admin_can_add_new_users()
    {
        $this->withoutExceptionHandling();
        $user = \App\Models\User::factory()->create();
        $user->assignRole('admin');
        $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $userFake = \App\Models\User::factory()->make();
        $response = $this->post('/api/users', [
            'name' => $userFake->name,
            'email' => $userFake->email,
            'password' => 'password',
            'password_confirmation' => 'password',
            'username' => $userFake->username,
        ]);
        $response->assertStatus(201);
    }
}
