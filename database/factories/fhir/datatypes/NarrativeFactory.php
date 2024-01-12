<?php

namespace Database\Factories\Fhir\Datatypes;

use App\Models\Fhir\Datatypes\Narrative;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class NarrativeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => fake()->randomElement(Narrative::STATUS['binding']['valueset']['code']),
            'div' => fake()->paragraph(),
        ];
    }
}
