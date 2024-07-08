<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FhirResource>
 */
class FhirResourceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'resourceType' => array_rand(config('app.resourceTypes')),
            'id' => fake()->uuid(),
        ];
    }

    public function specific(string $resourceType): Factory
    {
        return $this->state(function (array $attributes) use ($resourceType) {
            return [
                'resourceType' => $resourceType,
            ];
        });
    }
}
