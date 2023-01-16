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
        $this->post('/api/reservations', $reservation->toArray());
        $this->assertDatabaseHas('reservations', $reservation->toArray());
    }
    public function test_user_cannot_make_reservations_on_timeslots_taken()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($user = \App\Models\User::factory()->create());
        $facility = \App\Models\Facility::factory()->create();
        $reservation = \App\Models\Reservation::factory()->for($facility)->for($user)->make();

        $this->post('/api/reservations', $reservation->toArray());
        $this->assertDatabaseHas('reservations', $reservation->toArray());
    }
}
