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
    }
}
