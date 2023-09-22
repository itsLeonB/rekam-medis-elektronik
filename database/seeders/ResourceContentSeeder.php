<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ResourceContent;
use App\Models\Resource;
use Illuminate\Support\Facades\Storage;

class ResourceContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $count = 0;
        $resId = Resource::pluck('res_id');
        $files = Storage::disk('fhir-example')->files('Patient');
        foreach ($files as $f) {
            $id = $resId[$count];
            $res_content = file_get_contents(storage_path('fhir-example') . '/' . $f);
            ResourceContent::create(
                [
                    'res_id' => $id,
                    'res_text' => $res_content
                ]
            );
            $count++;
        }
        $resourceTypes = ['Patient', 'Practitioner'];
        foreach ($resourceTypes as $resource) {
            $files = Storage::disk('fhir-example')->files($resource);
            foreach ($files as $f) {
                $fname = str_replace($resource . '/', '', $f);
                list($res_type, $forced_id) = explode('-', $fname, 2);
                list($forced_id, $ext) = explode('.', $forced_id, 2);
                Resource::create(
                    [
                        'res_type' => $res_type
                    ]
                );
            }
        }
    }
}
