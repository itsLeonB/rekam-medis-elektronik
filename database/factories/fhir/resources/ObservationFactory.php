<?php

namespace Database\Factories\Fhir\Resources;

use App\Models\Fhir\Resource;
use App\Models\Fhir\Resources\Observation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;


class ObservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = fake()->randomElement(Observation::STATUS['binding']['valueset']['code']);

        return [
            'resource_id' => Resource::factory()->create(['res_type' => 'Observation']),
            'status' => $status,
            'issued' => fake()->dateTime(),
            'effective_date_time' => fake()->dateTime(),
            'value_string' => fake()->sentence(),
        ];
    }
}
