<?php

namespace Database\Factories\Fhir\Datatypes;

use App\Fhir\Codesystems;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class DurationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $code = DB::table(Codesystems::UCUM['table'])->inRandomOrder()->first();

        return [
            'value' => fake()->randomFloat(2, 0, 1000),
            'comparator' => fake()->randomElement(['<', '<=', '>=', '>']),
            'unit' => $code->unit,
            'system' => Codesystems::UCUM['system'],
            'code' => $code->code,
        ];
    }
}
