<?php

namespace Database\Factories\Fhir;

use App\Models\Fhir\AllergyIntolerance;
use App\Models\Fhir\Resource;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
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
            "code_system" => "http://snomed.info/sct",
            "code_code" => "89811004",
            "code_display" => "Gluten (substance)",
            'patient' => 'Patient/' . fake()->uuid(),
        ];
    }
}
