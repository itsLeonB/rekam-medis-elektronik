<?php

namespace Database\Factories\Fhir;

use App\Models\Fhir\Resource;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResourceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $resTypes = array_keys(config('app.resource_type_map'));
        $resType = $resTypes[array_rand($resTypes)];

        return [
            'satusehat_id' => fake()->uuid(),
            'res_type' => $resType,
            'res_version' => 1,
            'fhir_ver' => 'R4'
        ];
    }
}
