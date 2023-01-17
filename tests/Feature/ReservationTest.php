<?php

namespace Tests\Feature;

use Database\Seeders\InitialSetupSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(InitialSetupSeeder::class);
    }
    /**t
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_make_reservation_for_facility()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($user = \App\Models\User::factory()->create());
        $facility = \App\Models\Facility::factory()->create();
        $reservation = \App\Models\Reservation::factory()->make([
            'facility_id' => $facility->id,
            'user_id' => $user->id,
        ]);
        // dd($reservation);
        $this->post('/api/reservations', $reservation->toArray())->assertStatus(201);
        $this->assertDatabaseHas('reservations', $reservation->toArray());
    }
    public function test_user_cannot_reserve_already_booked_time_slot()
    {
        $this->withoutExceptionHandling();
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);
        $facility = \App\Models\Facility::factory()->create();
        $reservation = \App\Models\Reservation::factory()->create([
            'facility_id' => $facility->id,
            'user_id' => $user->id,
        ]);
        $reservation2 = \App\Models\Reservation::factory()->make([
            'facility_id' => $facility->id,
            'user_id' => $user->id,
            'start_time' => $reservation->start_time,
            'reservation_date' => $reservation->reservation_date,
        ]);
        $this->post('/api/reservations', $reservation2->toArray())
            ->assertStatus(422);
        $this->assertDatabaseMissing('reservations', $reservation2->toArray());
    }
    public function test_user_can_view_specific_reservation()
    {
        $this->withoutExceptionHandling();
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);
        $facility = \App\Models\Facility::factory()->create();
        $reservation = \App\Models\Reservation::factory()->create([
            'facility_id' => $facility->id,
            'user_id' => $user->id,
        ]);
        $this->get('/api/reservations/' . $reservation->id)
            ->assertStatus(200)
            ->assertJson($reservation->toArray());
    }
    // public function test_user_cannot_view_all_reservations()
    // {
    //     $this->withExceptionHandling();
    //     $user = \App\Models\User::factory()->create(['password' => bcrypt('password')]);
    //     $this->post('/api/login', [
    //         'email' => $user->email,
    //         'password' => 'password'
    //     ]);
    //     $facility = \App\Models\Facility::factory()->create();
    //     $reservation = \App\Models\Reservation::factory()->create([
    //         'facility_id' => $facility->id,
    //         'user_id' => $user->id,
    //     ]);
    //     $this->get('/api/reservations')
    //         ->assertStatus(403)
    //         ->assertJsonMissing($reservation->toArray());
    // }
    public function test_authorized_users_can_view_all_reservations()
    {
        $this->withoutExceptionHandling();
        $user = \App\Models\User::factory()->create(['password' => bcrypt('password')]);
        $this->actingAs($user);
        $user->givePermissionTo('view-reservations');
        $facility = \App\Models\Facility::factory()->create();
        $reservation = \App\Models\Reservation::factory()->create([
            'facility_id' => $facility->id,
            'user_id' => $user->id,
        ]);
        $this->get('/api/reservations')
            ->assertStatus(200);
    }

    public function test_users_can_view_related_reservations()
    {
        $this->withoutExceptionHandling();
        $user = \App\Models\User::factory()->create(['password' => bcrypt('password')]);
        $this->actingAs($user);
        $facility = \App\Models\Facility::factory()->create();
        $reservation = \App\Models\Reservation::factory()->create([
            'facility_id' => $facility->id,
            'user_id' => $user->id,
        ]);
        $this->get('/api/reservations/user/' . $user->username)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'user_id',
                        'facility_id',
                        'reservation_date',
                        'start_time',
                        'end_time',
                        'created_at',
                        'updated_at',
                    ],
                ],
            ]);
    }
    public function test_users_can_cancel_their_reservation()
    {
        $this->withoutExceptionHandling();
        $user = \App\Models\User::factory()->create(['password' => bcrypt('password')]);
        $this->actingAs($user);
        $facility = \App\Models\Facility::factory()->create();
        $reservation = \App\Models\Reservation::factory()->create([
            'facility_id' => $facility->id,
            'user_id' => $user->id,
        ]);
        $this->post('/api/reservations/cancel/' . $reservation->id)
            ->assertStatus(200);
        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'status' => 'cancelled',
        ]);
    }
}
