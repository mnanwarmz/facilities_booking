<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_make_payment_for_reservation()
    {
        $this->withoutExceptionHandling();

        $this->actingAs(\App\Models\User::factory()->create());
        $facility = \App\Models\Facility::factory()->create();

        $reservation = \App\Models\Reservation::factory()->create([
            'facility_id' => $facility->id,
            'user_id' => auth()->id(),
        ]);

        $response = $this->post('/api/payments', [
            'method' => 'cash',
            'amount' => 100,
            'reservation_id' => $reservation->id,
            'user_id' => auth()->id(),
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('payments', [
            'method' => 'cash',
            'amount' => 100,
            'reservation_id' => $reservation->id,
            'user_id' => auth()->id(),
        ]);
    }
}
