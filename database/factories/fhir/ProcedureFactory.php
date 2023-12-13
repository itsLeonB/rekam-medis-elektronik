<?php

namespace Database\Factories\Fhir;

use App\Fhir\Valuesets;
use App\Models\Fhir\Procedure;
use App\Models\Fhir\Resource;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
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
            'code_code' => $code,
            'subject' => 'Patient/' . fake()->uuid(),
            'encounter' => 'Encounter/' . fake()->uuid(),
        ];
    }
}
