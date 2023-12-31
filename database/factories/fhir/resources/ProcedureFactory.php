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
        $resource = Resource::factory()->create(['res_type' => 'Procedure']);

        $statuses = Procedure::STATUS['binding']['valueset']['code'];
        $status = $statuses[array_rand($statuses)];

        $codes = Valuesets::KemkesClinicalTerm['code'];
        $code = $codes[array_rand($codes)];

        return [
            'resource_id' => $resource->id,
            'status' => $status,
        ];
    }
}
