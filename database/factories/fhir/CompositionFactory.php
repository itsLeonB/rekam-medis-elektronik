<?php

namespace Database\Factories\Fhir;

use App\Fhir\Codesystems;
use App\Models\Fhir\Composition;
use App\Models\Fhir\Resource;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
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
            'type_system' => Codesystems::LOINC['system'],
            'type_code' => $type->code,
            'type_display' => $type->display,
            'subject' => 'Patient/' . fake()->uuid(),
            'date' => now(),
            'title' => fake()->sentence(),
        ];
    }
}
