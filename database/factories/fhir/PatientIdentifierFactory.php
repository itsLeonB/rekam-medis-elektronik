<?php

namespace Database\Factories\Fhir;

use Illuminate\Database\Eloquent\Factories\Factory;

class PatientIdentifierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'system' => 'rme',
            'use' => 'usual',
            'value' => fake()->randomNumber(6, false)
        ];
    }
}
