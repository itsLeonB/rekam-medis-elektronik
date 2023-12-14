<?php

namespace Database\Factories\Fhir;

use App\Models\Fhir\Resource;
use Illuminate\Database\Eloquent\Factories\Factory;


class QuestionnaireResponseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $resource = Resource::factory()->create(['res_type' => 'QuestionnaireResponse']);

        return [
            'resource_id' => $resource->id,
        ];
    }
}
