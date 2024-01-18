<?php

namespace Database\Factories\Fhir\Resources;

use App\Models\Fhir\Datatypes\CodeableConcept;
use App\Models\Fhir\Datatypes\Coding;
use App\Models\Fhir\Datatypes\Extension;
use App\Models\Fhir\Datatypes\Identifier;
use App\Models\Fhir\Datatypes\Ratio;
use App\Models\Fhir\Resource;
use App\Models\Fhir\Resources\Medication;
use Illuminate\Database\Eloquent\Factories\Factory;

class MedicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'resource_id' => Resource::factory()->create(['res_type' => 'Medication']),
            'status' => fake()->randomElement(Medication::STATUS['binding']['valueset']['code']),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Medication $medication) {
            Identifier::factory()->for($medication, 'identifiable')->create(['attr_type' => 'identifier']);
            $form = CodeableConcept::factory()->for($medication, 'codeable')->create(['attr_type' => 'form']);
            Coding::factory()->medicationForm()->for($form, 'codeable')->create(['attr_type' => 'coding']);
            Ratio::factory()->for($medication, 'rateable')->create(['attr_type' => 'amount']);
            $medType = Extension::factory()->for($medication, 'extendable')->create([
                'url' => 'https://fhir.kemkes.go.id/r4/StructureDefinition/MedicationType',
                'attr_type' => 'medicationType'
            ]);
            $code = CodeableConcept::factory()->for($medType, 'codeable')->create(['attr_type' => 'valueCodeableConcept']);
            Coding::factory()->medicationType()->for($code, 'codeable')->create(['attr_type' => 'coding']);
        });
    }
}
