<?php

namespace Database\Factories\Fhir\BackboneElements;

use App\Models\Fhir\BackboneElements\EncounterLocation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class EncounterLocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => fake()->randomElement(EncounterLocation::STATUS['binding']['valueset']['code']),
        ];
    }
}
