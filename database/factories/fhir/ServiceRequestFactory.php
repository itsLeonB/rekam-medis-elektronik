<?php

namespace Database\Factories\Fhir;

use App\Fhir\Codesystems;
use App\Models\Fhir\Resource;
use App\Models\Fhir\ServiceRequest;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
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
            'code_system' => Codesystems::LOINC['system'],
            'code_code' => $code->code,
            'code_display' => $code->display,
            'subject' => 'Patient/' . fake()->uuid(),
            'encounter' => 'Encounter/' . fake()->uuid(),
        ];
    }
}
