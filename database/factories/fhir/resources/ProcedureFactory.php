<?php

namespace Database\Factories\Fhir\Resources;

use App\Fhir\Valuesets;
use App\Models\Fhir\Resource;
use App\Models\Fhir\Resources\Procedure;
use Illuminate\Database\Eloquent\Factories\Factory;


class ProcedureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = fake()->randomElement(Procedure::STATUS['binding']['valueset']['code']);

        return [
            'resource_id' => Resource::factory()->create(['res_type' => 'Procedure']),
            'status' => $status,
            'performed_date_time' => fake()->dateTime()
        ];
    }
}
