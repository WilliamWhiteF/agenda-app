<?php

namespace Database\Factories;

use App\Domains\Appointments\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $initialDate = Carbon::parse(fake()->dateTime());

        $endDate = clone $initialDate;
        $endDate->addHour();

        return [
            'start_date' => $initialDate->format('Y-m-d H:i:s'),
            'end_date' => $endDate->format('Y-m-d H:i:s'),
            'deadline_date' => $endDate->format('Y-m-d H:i:s'),
            'status' => false,
            'title' => Str::random(25),
            'type' => Str::random(25),
            'description' => Str::random(100),
        ];
    }

    public function done(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => true,
        ]);
    }
}
