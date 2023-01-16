<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

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
}
