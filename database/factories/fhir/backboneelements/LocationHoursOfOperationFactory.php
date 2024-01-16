<?php

namespace Database\Factories\Fhir\BackboneElements;

use App\Models\Fhir\BackboneElements\LocationHoursOfOperation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class LocationHoursOfOperationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'days_of_week' => [fake()->randomElement(LocationHoursOfOperation::DAYS_OF_WEEK['binding']['valueset']['code'])],
            'all_day' => fake()->boolean(),
            'opening_time' => fake()->time(),
            'closing_time' => fake()->time(),
        ];
    }
}
