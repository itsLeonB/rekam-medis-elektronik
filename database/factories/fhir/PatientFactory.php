<?php

namespace Database\Factories\Fhir;

use App\Models\Fhir\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
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
        $gender = $genders[array_rand($genders)];

        return [
            'active' => fake()->boolean(),
            'gender' => $gender,
            'multiple_birth' => ['multipleBirthBoolean' => fake()->boolean()],
        ];
    }
}
