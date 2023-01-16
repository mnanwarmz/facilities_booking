<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FacilitiesTest extends TestCase
{
    use RefreshDatabase;

    // setup
    public function setUp(): void
    {
        parent::setUp();
        $this->app->make(\Spatie\Permission\PermissionRegistrar::class)->registerPermissions();
        $this->seed('InitialSetupSeeder');
    }
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
        // Create 10 facilities
        $facilities = \App\Models\Facility::factory()->count(10)->create();
        // Get facilitiesµ
        $response = $this->get('/api/facilities');
        // Assert status code
        $response->assertStatus(200);

        // Assert return json data
        $response->assertJson([
            'data' => $facilities->toArray()
        ]);
    }

    public function test_unauthorized_users_cannot_add_facilities()
    {
        $user = \App\Models\User::factory()->create([
            'password' => bcrypt('password')
        ]);
        // Login user
        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);
        // Create facility
        $facility = \App\Models\Facility::factory()->make();
        // Add facility
        $response = $this->post('/api/facilities', $facility->toArray());
        $response->assertStatus(403);
    }

    public function test_authorized_users_can_add_facilities()
    {
        $user = \App\Models\User::factory()->create([
            'password' => bcrypt('password')
        ]);
        // Login user
        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);
        // Assign role to user
        $user->assignRole('admin');
        // Create facility
        $facility = \App\Models\Facility::factory()->make();
        // Add facility
        $response = $this->post('/api/facilities', $facility->toArray());
        $response->assertStatus(200);
        $response->assertJson([
            'data' => $facility->toArray()
        ]);
        $this->assertDatabaseHas('facilities', $facility->toArray());
    }

    public function test_unauthorized_users_cannot_edit_facilities()
    {
        $user = \App\Models\User::factory()->create([
            'password' => bcrypt('password')
        ]);
        // Login user
        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);
        // Create facility
        $facility = \App\Models\Facility::factory()->create();
        $facilityEdited = \App\Models\Facility::factory()->make();
        // Edit facility
        $response = $this->post('/api/facilities/' . $facility->id, $facilityEdited->toArray());
        $response->assertStatus(403);
        $this->assertDatabaseMissing('facilities', $facilityEdited->toArray());
    }

    public function test_authorized_users_can_edit_facilities()
    {
        $user = \App\Models\User::factory()->create([
            'password' => bcrypt('password')
        ]);
        // Login user
        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);
        // Assign role to user
        $user->assignRole('admin');
        // Create facility
        $facility = \App\Models\Facility::factory()->create();
        $facilityEdited = \App\Models\Facility::factory()->make();
        // Edit facility
        $response = $this->post('/api/facilities/' . $facility->id, $facilityEdited->toArray());
        $response->assertStatus(200);
        $response->assertJson([
            'data' => $facilityEdited->toArray()
        ]);
        $this->assertDatabaseHas('facilities', $facilityEdited->toArray());
    }
}
