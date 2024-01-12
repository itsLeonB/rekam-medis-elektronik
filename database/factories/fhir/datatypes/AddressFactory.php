<?php

namespace Database\Factories\Fhir\Datatypes;

use App\Models\Fhir\Datatypes\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'use' => fake()->randomElement(Address::USE['binding']['valueset']['code']),
            'type' => fake()->randomElement(Address::TYPE['binding']['valueset']['code']),
            'text' => fake()->address(),
            'line' => [fake()->streetAddress()],
            'postal_code' => fake()->postcode(),
            'country' => 'ID',
        ];
    }
}
