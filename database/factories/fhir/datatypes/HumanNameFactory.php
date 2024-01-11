<?php

namespace Database\Factories\Fhir\Datatypes;

use App\Models\Fhir\Datatypes\Identifier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class HumanNameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $uses = Identifier::USE['binding']['valueset']['code'];
        $use = fake()->randomElement($uses);

        return [
            'use' => $use,
            'text' => fake()->name(),
            'family' => fake()->lastName(),
            'given' => [fake()->firstName()],
            'prefix' => [fake()->title()],
            'suffix' => [fake()->suffix()]
        ];
    }
}
