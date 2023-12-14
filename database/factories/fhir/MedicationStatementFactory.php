<?php

namespace Database\Factories\Fhir;

use App\Models\Fhir\Resource;
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
        $resource = Resource::factory()->create(['res_type' => 'MedicationStatement']);

        return [
            'resource_id' => $resource->id,
            'medication' => ['medicationReference' => ['reference' => 'Medication/' . fake()->uuid()]],
            'subject' => 'Patient/' . fake()->uuid(),
        ];
    }
}
