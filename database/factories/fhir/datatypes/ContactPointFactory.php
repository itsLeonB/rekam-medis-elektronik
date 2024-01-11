<?php

namespace Database\Factories\Fhir\Datatypes;

use App\Models\Fhir\Datatypes\ContactPoint;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ContactPointFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'use' => fake()->randomElement(ContactPoint::USE['binding']['valueset']['code'])
        ];
    }

    public function phone(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'system' => fake()->randomElement(['phone', 'mobile', 'fax', 'sms']),
                'value' => fake()->phoneNumber()
            ];
        });
    }

    public function email(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'system' => 'email',
                'value' => fake()->email()
            ];
        });
    }

    public function url(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'system' => 'url',
                'value' => fake()->url()
            ];
        });
    }
}
