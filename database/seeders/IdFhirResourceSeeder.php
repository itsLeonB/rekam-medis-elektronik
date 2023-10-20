<?php

namespace Database\Seeders;

use App\Models\Resource;
use App\Models\ResourceContent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class IdFhirResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $files = Storage::disk('example-id-fhir')->files();

        foreach ($files as $f) {
            $resText = file_get_contents(storage_path('example-id-fhir') . '/' . $f);
            list($resType, $satusehatId) = explode('-', $f, 2);
            list($satusehatId, $ext) = explode('.', $satusehatId, 2);

            $res = Resource::create(
                [
                    'satusehat_id' => $satusehatId,
                    'res_type' => $resType
                ]
            );

            ResourceContent::create(
                [
                    'resource_id' => $res->id,
                    'res_text' => $resText
                ]
            );
        }
    }
}
