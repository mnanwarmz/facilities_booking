<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FacilityTypeTest extends TestCase
{
    use RefreshDatabase;

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
    public function test_admin_can_create_new_facility_type()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($user = User::factory()->create());
        $user->assignRole('admin');
        $factory = \App\Models\FacilityType::factory()->make();
        $this->post('/api/facility-types', $factory->toArray());
        $this->assertDatabaseHas('facility_types', $factory->toArray());
    }

    public function test_can_get_all_facility_types()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($user = User::factory()->create());
        $user->assignRole('admin');
        $factory = \App\Models\FacilityType::factory()->count(10)->create();
        $response = $this->get('/api/facility-types');
        $response->assertStatus(200);
        $response->assertJson([
            'data' => $factory->toArray()
        ]);
    }

    public function test_can_view_specific_facility_type()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($user = User::factory()->create());
        $user->assignRole('admin');
        $factory = \App\Models\FacilityType::factory()->create();
        $response = $this->get('/api/facility-types/' . $factory->id);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => $factory->toArray()
        ]);
    }

    public function test_admin_can_deactivate_facility_type()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($user = User::factory()->create());
        $user->assignRole('admin');
        $factory = \App\Models\FacilityType::factory()->create();
        $response = $this->post('/api/facility-types/deactivate/' . $factory->id);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => $factory->toArray()
        ]);
        $this->assertDatabaseHas('facility_types', [
            'id' => $factory->id,
            'is_active' => false
        ]);
    }

    public function test_admin_can_update_facility_type()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($user = User::factory()->create());
        $user->assignRole('admin');
        $factory = \App\Models\FacilityType::factory()->create();
        $response = $this->post('/api/facility-types/' . $factory->id, [
            'name' => 'Updated Facility Type',
            'description' => 'Updated Facility Type'
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'name' => 'Updated Facility Type',
                'description' => 'Updated Facility Type',
            ]
        ]);
        $this->assertDatabaseHas('facility_types', [
            'id' => $factory->id,
            'name' => 'Updated Facility Type'
        ]);
    }
}
