<?php

namespace Database\Factories\Fhir\Resources;

use App\Models\Fhir\Resource;
use App\Models\Fhir\Resources\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

class PatientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'resource_id' => Resource::factory()->create(['res_type' => 'Patient']),
            'active' => fake()->boolean(),
            'gender' => fake()->randomElement(Patient::GENDER['binding']['valueset']),
            'birth_date' => fake()->date(),
            'deceased_boolean' => fake()->boolean(),
            'multiple_birth_integer' => fake()->randomDigit(),
        ];
    }
}
