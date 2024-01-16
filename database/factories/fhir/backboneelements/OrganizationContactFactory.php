<?php

namespace Database\Factories\Fhir\BackboneElements;

use App\Models\Fhir\BackboneElements\OrganizationContact;
use App\Models\Fhir\Datatypes\Address;
use App\Models\Fhir\Datatypes\CodeableConcept;
use App\Models\Fhir\Datatypes\Coding;
use App\Models\Fhir\Datatypes\ContactPoint;
use App\Models\Fhir\Datatypes\HumanName;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class OrganizationContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (OrganizationContact $orgContact) {
            $purpose = CodeableConcept::factory()->for($orgContact, 'codeable')->create(['attr_type' => 'purpose']);
            Coding::factory()->organizationContactPurpose()->for($purpose, 'codeable')->create(['attr_type' => 'coding']);
            HumanName::factory()->for($orgContact, 'humanNameable')->create(['attr_type' => 'name']);
            ContactPoint::factory()->for($orgContact, 'contactPointable')->create(['attr_type' => 'telecom']);
            Address::factory()->for($orgContact, 'addressable')->create(['attr_type' => 'address']);
        });
    }
}
