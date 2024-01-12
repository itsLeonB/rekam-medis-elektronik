<?php

namespace Database\Factories\Fhir\Resources;

use App\Models\Fhir\Resource;
use App\Models\Fhir\Resources\MedicationStatement;
use Illuminate\Database\Eloquent\Factories\Factory;


class MedicationStatementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'resource_id' => Resource::factory()->create(['res_type' => 'MedicationStatement']),
            'status' => fake()->randomElement(MedicationStatement::STATUS['binding']['valueset']['code']),
            'effective_date_time' => fake()->dateTime(),
            'date_asserted' => fake()->dateTime(),
        ];
    }
}
