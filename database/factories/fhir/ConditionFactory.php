<?php

namespace Database\Factories\Fhir;

use App\Models\Fhir\Resource;
use Illuminate\Database\Eloquent\Factories\Factory;


class ConditionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $resource = Resource::factory()->create(['res_type' => 'Condition']);

        return [
            'resource_id' => $resource->id,
            'subject' => 'Patient/' . fake()->uuid(),
            'encounter' => 'Encounter/' . fake()->uuid(),
        ];
    }
}
