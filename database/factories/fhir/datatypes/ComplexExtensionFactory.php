<?php

namespace Database\Factories\Fhir\Datatypes;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ComplexExtensionFactory extends Factory
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

    public function administrativeCode(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'url' => 'https://fhir.kemkes.go.id/r4/StructureDefinition/AdministrativeCode',
                'exts' => ['province', 'city', 'district', 'village', 'rt', 'rw']
            ];
        });
    }

    public function geolocation(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'url' => 'https://fhir.kemkes.go.id/r4/StructureDefinition/geolocation',
                'exts' => ['latitude', 'longitude']
            ];
        });
    }
}
