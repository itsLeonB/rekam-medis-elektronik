<?php

namespace Database\Factories\Fhir\Resources;

use App\Models\Fhir\Resource;
use App\Models\Fhir\Resources\ClinicalImpression;
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
        return [
            'resource_id' => Resource::factory()->create(['res_type' => 'ClinicalImpression']),
            'status' => fake()->randomElement(ClinicalImpression::STATUS['binding']['valueset']['code']),
            'description' => fake()->sentence(),
            'effective_date_time' => fake()->dateTime(),
            'date' => fake()->dateTime(),
            'summary' => fake()->sentence(),
        ];
    }
}
