<?php

namespace Database\Factories\Fhir;

use App\Models\Fhir\Encounter;
use App\Models\Fhir\Resource;
use Illuminate\Database\Eloquent\Factories\Factory;


class EncounterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $resource = Resource::factory()->create(['res_type' => 'Encounter']);

        $statuses = Encounter::STATUS['binding']['valueset']['code'];
        $status = $statuses[array_rand($statuses)];

        $classes = Encounter::ENC_CLASS['binding']['valueset']['code'];
        $class = $classes[array_rand($classes)];

        return [
            'resource_id' => $resource->id,
            'status' => $status,
            'class' => $class,
            'service_type' => fake()->numberBetween(1, 629),
            'subject' => 'Patient/' . fake()->uuid(),
            'period_start' => fake()->dateTimeBetween('-1 year', 'now', 'Asia/Jakarta'),
            'service_provider' => 'Organization/' . config('app.organization_id'),
        ];
    }
}
