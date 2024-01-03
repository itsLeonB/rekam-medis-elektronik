<?php

namespace Database\Factories;

use App\Models\Fhir\Practitioner;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PractitionerUser>
 */
class PractitionerUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::factory()->create();
        $practitioner = Practitioner::factory()->create();
        return [
            'user_id' => $user->id,
            'practitioner_id' => $practitioner->id
        ];
    }
}
