<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Resource;
use Illuminate\Support\Facades\Storage;

class ResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $files = Storage::disk('fhir-example')->files('Patient');
        foreach ($files as $f) {
            $fname = str_replace('Patient/', '', $f);
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
