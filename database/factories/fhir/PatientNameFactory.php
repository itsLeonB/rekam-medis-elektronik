<?php

namespace Database\Factories\Fhir;

use App\Models\Fhir\PatientName;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PatientNameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $use = array_rand(PatientName::USE['binding']['valueset']['code']);

        return [
            'use' => $use,
            'text' => fake()->name(),
        ];
    }
}
