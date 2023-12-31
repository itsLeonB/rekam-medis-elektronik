<?php

namespace Database\Factories\Fhir\Datatypes;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PeriodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'start' => Carbon::createFromTimeStamp(fake()->dateTimeBetween('-1 year', 'now')->getTimestamp())
                ->setTimezone('Asia/Jakarta') // Set the timezone explicitly
                ->format('Y-m-d\TH:i:sP'),
            'end' => Carbon::createFromTimeStamp(fake()->dateTimeBetween('now', '+1 year')->getTimestamp())
                ->setTimezone('Asia/Jakarta') // Set the timezone explicitly
                ->format('Y-m-d\TH:i:sP'),
        ];
    }
}
