<?php

namespace Database\Factories\Fhir\BackboneElements;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class LocationPositionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'longitude' => fake()->randomFloat(6, 0, 180),
            'latitude' => fake()->randomFloat(6, 0, 90),
            'altitude' => fake()->randomFloat(6, 0, 1000),
        ];
    }
}
