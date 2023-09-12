<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use App\Models\ResourceForcedId;
use App\Models\Resource;

class ResourceForcedIdSeeder extends Seeder
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
            $fname = str_replace('Patient/', '', $f);
            list($res_type, $forced_id) = explode('-', $fname, 2);
            list($forced_id, $ext) = explode('.', $forced_id, 2);
            ResourceForcedId::create(
                [
                    'res_id' => $id,
                    'forced_id' => $forced_id
                ]
            );
            $count++;
        }
    }
}
