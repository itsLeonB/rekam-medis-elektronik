<?php

namespace Database\Factories\Fhir\Resources;

use App\Models\Fhir\Resource;
use App\Models\Fhir\Resources\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

class PatientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $genders = Patient::GENDER['binding']['valueset'];
        $gender = fake()->randomElement($genders);

        return [
            'active' => fake()->boolean(),
            'gender' => $gender,
            'birth_date' => fake()->date(),
            'deceased_boolean' => fake()->boolean(),
            'multiple_birth_boolean' => fake()->boolean(),
        ];
    }
}
