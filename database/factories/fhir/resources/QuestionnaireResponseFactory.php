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
        $resource = Resource::factory()->create(['res_type' => 'QuestionnaireResponse']);
        $statuses = QuestionnaireResponse::STATUS['binding']['valueset']['code'];
        $status = $statuses[array_rand($statuses)];

        return [
            'resource_id' => $resource->id,
            'status' => $status,
        ];
    }
}
