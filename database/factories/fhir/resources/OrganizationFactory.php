<?php

namespace Database\Factories\Fhir\Resources;

use App\Models\Fhir\BackboneElements\OrganizationContact;
use App\Models\Fhir\Datatypes\Address;
use App\Models\Fhir\Datatypes\CodeableConcept;
use App\Models\Fhir\Datatypes\Coding;
use App\Models\Fhir\Datatypes\ContactPoint;
use App\Models\Fhir\Datatypes\Identifier;
use App\Models\Fhir\Datatypes\Reference;
use App\Models\Fhir\Resource;
use App\Models\Fhir\Resources\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class OrganizationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'active' => fake()->boolean(),
            'name' => fake()->company(),
            'alias' => [fake()->company()],
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Organization $organization) {
            Identifier::factory()->for($organization, 'identifiable')->create(['attr_type' => 'identifier']);
            $codeable = CodeableConcept::factory()->for($organization, 'codeable')->create(['attr_type' => 'type']);
            Coding::factory()->organizationType()->for($codeable, 'codeable')->create(['attr_type' => 'coding']);
            ContactPoint::factory()->for($organization, 'contactPointable')->create(['attr_type' => 'telecom']);
            Address::factory()->for($organization, 'addressable')->create(['attr_type' => 'address']);
            Reference::factory()->for($organization, 'referenceable')->create([
                'reference' => 'Organization/' . config('app.organization_id'),
                'attr_type' => 'partOf'
            ]);
            OrganizationContact::factory()->for($organization, 'organization')->create();
        });
    }

    public function rawatJalan()
    {
        return $this->state(function (array $attributes) {
            return [
                'resource_id' => Resource::factory()->create([
                    'res_type' => 'Organization',
                    'satusehat_id' => config('app.rawat_jalan_org_id')
                ]),
            ];
        });
    }

    public function rawatInap()
    {
        return $this->state(function (array $attributes) {
            return [
                'resource_id' => Resource::factory()->create([
                    'res_type' => 'Organization',
                    'satusehat_id' => config('app.rawat_inap_org_id')
                ]),
            ];
        });
    }

    public function igd()
    {
        return $this->state(function (array $attributes) {
            return [
                'resource_id' => Resource::factory()->create([
                    'res_type' => 'Organization',
                    'satusehat_id' => config('app.igd_org_id')
                ]),
            ];
        });
    }
}
