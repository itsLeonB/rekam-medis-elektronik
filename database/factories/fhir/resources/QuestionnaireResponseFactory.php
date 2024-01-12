<?php

namespace Database\Factories\Fhir\Resources;

use App\Models\Fhir\Resource;
use App\Models\Fhir\Resources\QuestionnaireResponse;
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
        return [
            'resource_id' => Resource::factory()->create(['res_type' => 'QuestionnaireResponse']),
            'status' => fake()->randomElement(QuestionnaireResponse::STATUS['binding']['valueset']['code']),
            'authored' => fake()->dateTime(),
        ];
    }
}
