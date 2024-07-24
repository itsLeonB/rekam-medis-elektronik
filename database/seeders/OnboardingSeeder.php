<?php

namespace Database\Seeders;

use App\Fhir\Processor;
use App\Models\Fhir\Resource;
use App\Models\FhirResource;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class OnboardingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedOnboarding();
    }

    public function seedOnboarding()
    {
        $processor = new Processor();

        $files = Storage::disk('onboarding-resource')->files();

        foreach ($files as $f) {
            $resText = Storage::disk('onboarding-resource')->get($f);
            list($resType, $ext) = explode('.', $f, 2);

            switch ($resType) {
                case 'Organization':
                    $org = FhirResource::create([
                        'satusehat_id' => config('app.organization_id'),
                        'res_type' => $resType
                    ]);

                    $org->content()->create([
                        'res_text' => $resText,
                        'res_ver' => 1
                    ]);

                    $resText = json_decode($resText, true);
                    $orgData = $processor->generateOrganization($resText);
                    $orgData = $this->removeEmptyValues($orgData);
                    $processor->saveOrganization($org, $orgData);
                    $org->save();

                    break;
                case 'Location':
                    $loc = Resource::create([
                        'satusehat_id' => config('app.location_id'),
                        'res_type' => $resType
                    ]);

                    $loc->content()->create([
                        'res_text' => $resText,
                        'res_ver' => 1
                    ]);

                    $resText = json_decode($resText, true);
                    $locData = $processor->generateLocation($resText);
                    $locData = $this->removeEmptyValues($locData);
                    $processor->saveLocation($loc, $locData);
                    $loc->save();

                    break;
                case 'Practitioner':
                    $prac = Resource::create([
                        'satusehat_id' => 'N10000001',
                        'res_type' => $resType
                    ]);

                    $prac->content()->create([
                        'res_text' => $resText,
                        'res_ver' => 1
                    ]);

                    $resText = json_decode($resText, true);
                    $pracData = $processor->generatePractitioner($resText);
                    $pracData = $this->removeEmptyValues($pracData);
                    $processor->savePractitioner($prac, $pracData);
                    $prac->save();

                    break;
                case 'Medication':
                    $med = Resource::create([
                        'satusehat_id' => 'mock-medication',
                        'res_type' => $resType
                    ]);

                    $med->content()->create([
                        'res_text' => $resText,
                        'res_ver' => 1
                    ]);

                    $resText = json_decode($resText, true);
                    $medData = $processor->generateMedication($resText);
                    $medData = $this->removeEmptyValues($medData);
                    $processor->saveMedication($med, $medData);
                    $med->save();

                    break;
            }
        }
    }

    private function removeEmptyValues($array)
    {
        return array_filter($array, function ($value) {
            if (is_array($value)) {
                return !empty($this->removeEmptyValues($value));
            }
            return $value !== null && $value !== "";
        });
    }
}
