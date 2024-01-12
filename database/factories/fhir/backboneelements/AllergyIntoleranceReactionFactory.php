<?php

namespace Database\Factories\Fhir\BackboneElements;

use App\Models\Fhir\BackboneElements\AllergyIntoleranceReaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class AllergyIntoleranceReactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'description' => fake()->paragraph(),
            'onset' => fake()->dateTime(),
            'severity' => fake()->randomElement(AllergyIntoleranceReaction::SEVERITY['binding']['valueset']['code']),
        ];
    }
}
