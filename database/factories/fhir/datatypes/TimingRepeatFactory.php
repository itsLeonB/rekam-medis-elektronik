<?php

namespace Database\Factories\Fhir\Datatypes;

use App\Models\Fhir\Datatypes\TimingRepeat;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class TimingRepeatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'count' => fake()->randomDigitNotZero(),
            'count_max' => fake()->randomDigitNotZero() * 2,
            'duration' => fake()->randomDigit(),
            'duration_max' => fake()->randomDigit() * 2,
            'duration_unit' => fake()->randomElement(TimingRepeat::DURATION_UNIT['binding']['valueset']['code']),
            'frequency' => fake()->randomDigitNotZero(),
            'frequency_max' => fake()->randomDigitNotZero() * 2,
            'period' => fake()->randomDigit(),
            'period_max' => fake()->randomDigit() * 2,
            'period_unit' => fake()->randomElement(TimingRepeat::PERIOD_UNIT['binding']['valueset']['code']),
            'day_of_week' => [fake()->randomElement(TimingRepeat::DAY_OF_WEEK['binding']['valueset']['code'])],
            'time_of_day' => [fake()->time()],
            'when' => [fake()->randomElement(TimingRepeat::WHEN['binding']['valueset']['code'])],
            'offset' => fake()->randomDigit(),
        ];
    }
}
