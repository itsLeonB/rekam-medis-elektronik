<?php

namespace Database\Factories\Fhir;

use App\Models\Fhir\Encounter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class EncounterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = Encounter::STATUS['binding']['valueset']['code'];
        $status = $statuses[array_rand($statuses)];

        $classes = Encounter::ENC_CLASS['binding']['valueset']['code'];
        $class = $classes[array_rand($classes)];

        return [
            'status' => $status,
            'class' => $class,
            'service_type' => fake()->numberBetween(1, 629),
            'subject' => 'Patient/' . fake()->uuid(),
            'period_start' => fake()->dateTimeBetween('-1 year', 'now', 'Asia/Jakarta'),
            'service_provider' => 'Organization/' . config('app.organization_id'),
        ];
    }
}
