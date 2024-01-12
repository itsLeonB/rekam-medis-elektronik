<?php

namespace Database\Factories\Fhir\Resources;

use App\Models\Fhir\Resource;
use App\Models\Fhir\Resources\Encounter;
use Illuminate\Database\Eloquent\Factories\Factory;


class EncounterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['planned', 'arrived', 'triaged', 'in-progress', 'onleave'];
        $status = fake()->randomElement($statuses);

        return [
            'resource_id' => Resource::factory()->create([
                'res_type' => 'Encounter',
            ]),
            'status' => $status,
        ];
    }
}
