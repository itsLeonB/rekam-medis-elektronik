<?php

namespace Database\Factories\Fhir\Datatypes;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class IdentifierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'system' => config('app.identifier_systems.patient.rekam-medis'),
            'value' => fake()->numberBetween(000001, 999999),
        ];
    }
}
