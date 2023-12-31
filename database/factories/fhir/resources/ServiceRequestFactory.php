<?php

namespace Database\Factories\Fhir\Resources;

use App\Fhir\Codesystems;
use App\Models\Fhir\Resource;
use App\Models\Fhir\Resources\ServiceRequest;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;


class ServiceRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $resource = Resource::factory()->create(['res_type' => 'ServiceRequest']);

        $statuses = ServiceRequest::STATUS['binding']['valueset']['code'];
        $status = $statuses[array_rand($statuses)];

        $intents = ServiceRequest::INTENT['binding']['valueset']['code'];
        $intent = $intents[array_rand($intents)];

        $code = DB::table(Codesystems::LOINC['table'])->inRandomOrder()->first();

        return [
            'resource_id' => $resource->id,
            'status' => $status,
            'intent' => $intent,
        ];
    }
}
