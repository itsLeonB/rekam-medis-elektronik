<?php

namespace Database\Factories\Fhir;

use App\Models\Fhir\PatientName;
use Illuminate\Database\Eloquent\Factories\Factory;

class PatientNameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $uses = PatientName::USE['binding']['valueset']['code'];
        $use = $uses[array_rand($uses)];

        return [
            'use' => $use,
            'text' => fake()->name(),
        ];
    }
}
