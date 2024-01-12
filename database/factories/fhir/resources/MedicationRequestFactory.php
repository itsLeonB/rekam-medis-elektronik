<?php

namespace Database\Factories\Fhir\Resources;

use App\Models\Fhir\Resource;
use App\Models\Fhir\Resources\MedicationRequest;
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
        return [
            'resource_id' => Resource::factory()->create(['res_type' => 'MedicationRequest']),
            'status' => fake()->randomElement(MedicationRequest::STATUS['binding']['valueset']['code']),
            'intent' => fake()->randomElement(MedicationRequest::INTENT['binding']['valueset']['code']),
            'priority' => fake()->randomElement(MedicationRequest::PRIORITY['binding']['valueset']['code']),
            'do_not_perform' => fake()->boolean(),
            'reported_boolean' => fake()->boolean(),
            'authored_on' => fake()->dateTime(),
        ];
    }
}
