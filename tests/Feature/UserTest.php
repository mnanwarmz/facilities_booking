<?php

namespace Tests\Feature;

use Database\Seeders\InitialSetupSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
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

    public function test_admin_can_assign_role_to_users()
    {
        $this->withoutExceptionHandling();
        $user = \App\Models\User::factory()->create();
        $user->assignRole('admin');
        $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        // Get first spatie role
        $role = Role::first();
        $userFake = \App\Models\User::factory()->create();
        $response = $this->post('/api/users/' . $userFake->id . '/roles', [
            'user_id' => $userFake->id,
            'role_ids' => [$role->id],
        ]);
        $response->assertStatus(200);
    }

    public function test_admin_can_remove_multiple_roles_from_user()
    {
        $this->withoutExceptionHandling();
        $user = \App\Models\User::factory()->create();
        $user->assignRole('admin');
        $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        // Get first spatie role
        $role = Role::first();
        $userFake = \App\Models\User::factory()->create();
        $userFake->assignRole($role);
        $response = $this->post('/api/users/remove/' . $userFake->id . '/roles', [
            'role_ids' => [$role->id],
        ]);
        $response->assertStatus(200);
        // Assert role is misisng
        $this->assertDatabaseMissing('model_has_roles', [
            'role_id' => $role->id,
            'model_id' => $userFake->id,
        ]);
    }
}
