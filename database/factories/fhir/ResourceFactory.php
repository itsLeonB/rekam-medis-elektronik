<?php

namespace Database\Factories\Fhir;

use App\Models\Fhir\Resource;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ResourceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $resTypes = Resource::VALID_RESOURCE_TYPES;
        $resType = $resTypes[array_rand($resTypes)];

        return [
            'satusehat_id' => fake()->uuid(),
            'res_type' => $resType,
            'res_version' => 1,
            'fhir_ver' => 'R4'
        ];
    }
}
