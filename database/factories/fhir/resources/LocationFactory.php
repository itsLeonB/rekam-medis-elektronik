<?php

namespace Database\Factories\Fhir\Resources;

use App\Models\Fhir\BackboneElements\LocationHoursOfOperation;
use App\Models\Fhir\BackboneElements\LocationPosition;
use App\Models\Fhir\Datatypes\Address;
use App\Models\Fhir\Datatypes\CodeableConcept;
use App\Models\Fhir\Datatypes\Coding;
use App\Models\Fhir\Datatypes\ContactPoint;
use App\Models\Fhir\Datatypes\Extension;
use App\Models\Fhir\Datatypes\Identifier;
use App\Models\Fhir\Datatypes\Reference;
use App\Models\Fhir\Resource;
use App\Models\Fhir\Resources\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class LocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'resource_id' => Resource::factory()->create(['res_type' => 'Location']),
            'status' => fake()->randomElement(Location::STATUS['binding']['valueset']['code']),
            'name' => fake()->company(),
            'alias' => [fake()->company()],
            'description' => fake()->sentence(),
            'mode' => fake()->randomElement(Location::MODE['binding']['valueset']['code']),
            'availability_exceptions' => fake()->sentence(),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Location $location) {
            Identifier::factory()->rekamMedis()->for($location, 'identifiable')->create(['attr_type' => 'identifier']);
            Coding::factory()->locationOperationalStatus()->for($location, 'codeable')->create(['attr_type' => 'operationalStatus']);
            $type = CodeableConcept::factory()->for($location, 'codeable')->create(['attr_type' => 'type']);
            Coding::factory()->locationType()->for($type, 'codeable')->create(['attr_type' => 'coding']);
            ContactPoint::factory()->for($location, 'contactPointable')->create(['attr_type' => 'telecom']);
            Address::factory()->for($location, 'addressable')->create(['attr_type' => 'address']);
            $physicalType = CodeableConcept::factory()->for($location, 'codeable')->create(['attr_type' => 'physicalType']);
            Coding::factory()->locationPhysicalType()->for($physicalType, 'codeable')->create(['attr_type' => 'coding']);
            LocationPosition::factory()->for($location, 'location')->create();
            Reference::factory()->for($location, 'referenceable')->create([
                'reference' => 'Organization/' . config('app.organization_id'),
                'attr_type' => 'managingOrganization'
            ]);
            LocationHoursOfOperation::factory()->for($location, 'location')->create();
            $ext = Extension::factory()->for($location, 'extendable')->create([
                'url' => Location::SERVICE_CLASS['url'],
                'attr_type' => 'serviceClass'
            ]);
            $code = CodeableConcept::factory()->for($ext, 'codeable')->create(['attr_type' => 'valueCodeableConcept']);
            Coding::factory()->locationServiceClass()->for($code, 'codeable')->create(['attr_type' => 'coding']);
        });
    }
}
