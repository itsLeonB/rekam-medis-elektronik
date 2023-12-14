<?php

namespace Database\Factories\Fhir;

use App\Models\Fhir\ClinicalImpression;
use App\Models\Fhir\Resource;
use Illuminate\Database\Eloquent\Factories\Factory;


class ClinicalImpressionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $resource = Resource::factory()->create(['res_type' => 'ClinicalImpression']);

        $statuses = ClinicalImpression::STATUS['binding']['valueset']['code'];
        $status = $statuses[array_rand($statuses)];

        $prognoses = ClinicalImpression::PROGNOSIS_CODEABLE_CONCEPT['binding']['valueset']['code'];
        $prognosis = [$prognoses[array_rand($prognoses)]];

        return [
            'resource_id' => $resource->id,
            'status' => $status,
            'subject' => 'Patient/' . fake()->uuid(),
            'encounter' => 'Encounter/' . fake()->uuid(),
            'prognosis_codeable_concept' => $prognosis,
        ];
    }
}
