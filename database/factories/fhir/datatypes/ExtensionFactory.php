<?php

namespace Database\Factories\Fhir\Datatypes;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ExtensionFactory extends Factory
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

    public function province(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'url' => 'province',
                'value_code' => DB::table('codesystem_administrativearea')->inRandomOrder()->first()->kode_provinsi
            ];
        });
    }

    public function city(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'url' => 'city',
                'value_code' => DB::table('codesystem_administrativearea')->inRandomOrder()->first()->kode_kabko
            ];
        });
    }

    public function district(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'url' => 'district',
                'value_code' => DB::table('codesystem_administrativearea')->inRandomOrder()->first()->kode_kecamatan
            ];
        });
    }

    public function village(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'url' => 'village',
                'value_code' => DB::table('codesystem_administrativearea')->inRandomOrder()->first()->kode_kelurahan
            ];
        });
    }

    public function rt(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'url' => 'rt',
                'value_code' => fake()->randomNumber(3)
            ];
        });
    }

    public function rw(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'url' => 'rw',
                'value_code' => fake()->randomNumber(3)
            ];
        });
    }

    public function latitude(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'url' => 'latitude',
                'value_decimal' => fake()->latitude()
            ];
        });
    }

    public function longitude(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'url' => 'longitude',
                'value_decimal' => fake()->longitude()
            ];
        });
    }
}
