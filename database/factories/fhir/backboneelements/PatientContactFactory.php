<?php

namespace Database\Factories\Fhir\BackboneElements;

use App\Models\Fhir\BackboneElements\PatientContact;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PatientContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'gender' => fake()->randomElement(PatientContact::GENDER['binding']['valueset']),
        ];
    }
}
