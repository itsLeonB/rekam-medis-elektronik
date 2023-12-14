<?php

namespace Database\Factories\Fhir;

use App\Models\Fhir\MedicationRequest;
use App\Models\Fhir\Resource;
use Illuminate\Database\Eloquent\Factories\Factory;


class MedicationRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $resource = Resource::factory()->create(['res_type' => 'MedicationRequest']);

        $statuses = MedicationRequest::STATUS['binding']['valueset']['code'];
        $status = $statuses[array_rand($statuses)];

        $intents = MedicationRequest::INTENT['binding']['valueset']['code'];
        $intent = $intents[array_rand($intents)];

        return [
            'resource_id' => $resource->id,
            'status' => $status,
            'intent' => $intent,
            'medication' => 'Medication/' . fake()->uuid(),
            'subject' => 'Patient/' . fake()->uuid(),
        ];
    }
}
