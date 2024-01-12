<?php

namespace Database\Factories\Fhir\Resources;

use App\Fhir\Codesystems;
use App\Models\Fhir\Resource;
use App\Models\Fhir\Resources\ServiceRequest;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;


class ServiceRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'resource_id' => Resource::factory()->create(['res_type' => 'ServiceRequest']),
            'status' => fake()->randomElement(ServiceRequest::STATUS['binding']['valueset']['code']),
            'intent' => fake()->randomElement(ServiceRequest::INTENT['binding']['valueset']['code']),
            'priority' => fake()->randomElement(ServiceRequest::PRIORITY['binding']['valueset']['code']),
            'do_not_perform' => fake()->boolean(),
            'occurrence_date_time' => fake()->dateTime(),
            'as_needed_boolean' => fake()->boolean(),
            'authored_on' => fake()->dateTime(),
            'patient_instruction' => fake()->text(),
        ];
    }
}
