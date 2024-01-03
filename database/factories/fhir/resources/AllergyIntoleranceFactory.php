<?php

namespace Database\Factories\Fhir\Resources;

use App\Models\Fhir\Resource;
use App\Models\Fhir\Resources\AllergyIntolerance;
use Illuminate\Database\Eloquent\Factories\Factory;


class AllergyIntoleranceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $resource = Resource::factory()->create(['res_type' => 'AllergyIntolerance']);

        $categories = AllergyIntolerance::CATEGORY['binding']['valueset']['code'];
        $category = [$categories[array_rand($categories)]];

        return [
            'resource_id' => $resource->id,
            'category' => $category,
        ];
    }
}
