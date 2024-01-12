<?php

namespace Database\Factories\Fhir\Resources;

use App\Fhir\Codesystems;
use App\Models\Fhir\Resource;
use App\Models\Fhir\Resources\Composition;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;


class CompositionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'resource_id' => Resource::factory()->create(['res_type' => 'Composition']),
            'status' => fake()->randomElement(Composition::STATUS['binding']['valueset']['code']),
            'date' => fake()->dateTime(),
            'title' => fake()->sentence(),
        ];
    }
}
