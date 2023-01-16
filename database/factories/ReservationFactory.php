<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    protected $model = \App\Models\Reservation::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'facility_id' => \App\Models\Facility::factory(),
            'user_id' => \App\Models\User::factory(),
            'purpose' => $this->faker->sentence,
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'remarks' => $this->faker->sentence,
            'start_time' => $this->faker->time('H:i:s'),
            'end_time' => $this->faker->time('H:i:s'),
            'reservation_date' => $this->faker->date(),
        ];
    }
}
