<?php

namespace Database\Factories\Fhir;

use App\Models\Fhir\MedicationDispense;
use App\Models\Fhir\Resource;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class MedicationDispenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $resource = Resource::factory()->create(['res_type' => 'MedicationDispense']);

        $statuses = MedicationDispense::STATUS['binding']['valueset']['code'];
        $status = $statuses[array_rand($statuses)];

        return [
            'resource_id' => $resource->id,
            'status' => $status,
            'medication' => 'Medication/' . fake()->uuid(),
            'subject' => 'Patient/' . fake()->uuid(),
        ];
    }
}
