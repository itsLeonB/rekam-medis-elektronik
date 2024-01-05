<?php

namespace Database\Factories\Fhir\Resources;

use App\Models\Fhir\Resource;
use App\Models\Fhir\Resources\Practitioner;
use Illuminate\Database\Eloquent\Factories\Factory;

class PractitionerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $resource = Resource::factory()->create([
            'res_type' => 'Practitioner'
        ]);

        $genders = Practitioner::GENDER['binding']['valueset'];
        $gender = $genders[array_rand($genders)];

        return [
            'resource_id' => $resource->id,
            'gender' => $gender
        ];
    }
}
