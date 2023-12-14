<?php

namespace Database\Factories\Fhir;

use App\Models\Fhir\Patient;
use App\Models\Fhir\Resource;
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
        $resource = Resource::factory()->create(['res_type' => 'Patient']);

        $genders = Patient::GENDER['binding']['valueset'];
        $gender = $genders[array_rand($genders)];

        return [
            'resource_id' => $resource->id,
            'active' => fake()->boolean(),
            'gender' => $gender,
            'multiple_birth' => ['multipleBirthBoolean' => fake()->boolean()],
        ];
    }
}
