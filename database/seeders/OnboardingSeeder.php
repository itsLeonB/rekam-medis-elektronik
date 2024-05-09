<?php

namespace Database\Seeders;

use App\Models\FhirResource;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class OnboardingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $files = Storage::disk('onboarding-resource')->files();

        foreach ($files as $f) {
            $resText = Storage::disk('onboarding-resource')->get($f);
            $data = json_decode($resText, true);
            FhirResource::create($data);
        }
    }
}
