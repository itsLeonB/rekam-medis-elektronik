<?php

namespace Database\Factories\Fhir\Datatypes;

use App\Models\Fhir\Datatypes\Identifier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class IdentifierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'use' => fake()->randomElement(Identifier::USE['binding']['valueset']['code']),
        ];
    }

    public function nik(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'system' => config('app.identifier_systems.patient.nik'),
                'value' => (string) fake()->randomNumber(8) + (string) fake()->randomNumber(8)
            ];
        });
    }

    public function bpjs(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'system' => config('app.identifier_systems.patient.bpjs'),
                'value' => (string) fake()->randomNumber(6) + (string) fake()->randomNumber(7)
            ];
        });
    }

    public function paspor(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'system' => config('app.identifier_systems.patient.paspor'),
                'value' => fake()->randomNumber(9)
            ];
        });
    }

    public function kk(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'system' => config('app.identifier_systems.patient.kk'),
                'value' => (string) fake()->randomNumber(8) + (string) fake()->randomNumber(8)
            ];
        });
    }

    public function nikIbu(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'system' => config('app.identifier_systems.patient.nik-ibu'),
                'value' => (string) fake()->randomNumber(8) + (string) fake()->randomNumber(8)
            ];
        });
    }

    public function rekamMedis(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'system' => config('app.identifier_systems.patient.rekam-medis'),
                'value' => fake()->randomNumber(6)
            ];
        });
    }
}
