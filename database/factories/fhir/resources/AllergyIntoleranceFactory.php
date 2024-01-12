<?php

namespace Database\Factories\Fhir\Resources;

use App\Models\Fhir\Resource;
use App\Models\Fhir\Resources\AllergyIntolerance;
use Illuminate\Database\Eloquent\Factories\Factory;


class AllergyIntoleranceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'resource_id' => Resource::factory()->create(['res_type' => 'AllergyIntolerance']),
            'type' => fake()->randomElement(AllergyIntolerance::TYPE['binding']['valueset']['code']),
            'category' => [fake()->randomElement(AllergyIntolerance::CATEGORY['binding']['valueset']['code'])],
            'criticality' => fake()->randomElement(AllergyIntolerance::CRITICALITY['binding']['valueset']['code']),
            'onset_date_time' => fake()->dateTime(),
            'recorded_date' => fake()->dateTime(),
            'last_occurrence' => fake()->dateTime(),
        ];
    }
}
