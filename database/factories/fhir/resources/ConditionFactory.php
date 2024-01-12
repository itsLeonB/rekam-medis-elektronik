<?php

namespace Database\Factories\Fhir\Resources;

use App\Models\Fhir\Resource;
use Illuminate\Database\Eloquent\Factories\Factory;


class ConditionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'resource_id' => Resource::factory()->create(['res_type' => 'Condition']),
            'onset_date_time' => fake()->dateTime(),
            'abatement_date_time' => fake()->dateTime(),
            'recorded_date' => fake()->dateTime(),
        ];
    }
}
