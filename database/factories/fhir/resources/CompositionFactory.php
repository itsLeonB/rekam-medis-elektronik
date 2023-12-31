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
        $resource = Resource::factory()->create(['res_type' => 'Composition']);

        $statuses = Composition::STATUS['binding']['valueset']['code'];
        $status = $statuses[array_rand($statuses)];

        $type = DB::table(Codesystems::LOINC['table'])->inRandomOrder()->first();

        return [
            'resource_id' => $resource->id,
            'status' => $status,
            'date' => now(),
            'title' => fake()->sentence(),
        ];
    }
}
