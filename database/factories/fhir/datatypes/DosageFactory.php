<?php

namespace Database\Factories\Fhir\Datatypes;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class DosageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sequence' => fake()->randomDigitNotNull(),
            'text' => fake()->text(),
            'patient_instruction' => fake()->text(),
            'as_needed_boolean' => fake()->boolean(),
        ];
    }
}
