<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Resource;
use App\Models\ResourceContent;
use App\Models\ResourceForcedId;
use Illuminate\Support\Facades\Storage;

class ResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $resourceTypes = ['Patient', 'Practitioner', 'Organization', 'Location', 'Encounter'];

        foreach ($resourceTypes as $resource) {
            $files = Storage::disk('fhir-example')->files($resource);

            foreach ($files as $f) {
                $res_content = file_get_contents(storage_path('fhir-example') . '/' . $f);
                $fname = str_replace($resource . '/', '', $f);
                list($res_type, $forced_id) = explode('-', $fname, 2);
                list($forced_id, $ext) = explode('.', $forced_id, 2);

                $res = Resource::create(
                    [
                        'res_type' => $res_type
                    ]
                );

                ResourceContent::create(
                    [
                        'resource_id' => $res->id,
                        'res_text' => $res_content
                    ]
                );

                ResourceForcedId::create(
                    [
                        'resource_id' => $res->id,
                        'forced_id' => $forced_id
                    ]
                );
            }
        }
    }
}
