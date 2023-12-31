<?php

namespace Database\Factories\Fhir\Resources;

use App\Models\Fhir\Resource;
use App\Models\Fhir\Resources\Observation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;


class ObservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $resource = Resource::factory()->create(['res_type' => 'Observation']);

        $statuses = Observation::STATUS['binding']['valueset']['code'];
        $status = $statuses[array_rand($statuses)];

        $codes = Observation::CODE['binding']['valueset']['table'];
        $code = DB::table($codes)->inRandomOrder()->limit(1)->value('code');

        return [
            'resource_id' => $resource->id,
            'status' => $status,
        ];
    }
}
