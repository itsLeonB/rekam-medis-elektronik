<?php

namespace Database\Seeders;

use App\Fhir\Processor;
use App\Models\Fhir\Resource;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class IdFhirResourceSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $files = Storage::disk('example-id-fhir')->files();

            foreach ($files as $f) {
                $resText = Storage::disk('example-id-fhir')->get($f);
                list($resType, $satusehatId) = explode('-', $f, 2);
                list($satusehatId, $ext) = explode('.', $satusehatId, 2);

                $res = Resource::create(
                    [
                        'satusehat_id' => $satusehatId,
                        'res_type' => $resType
                    ]
                );

                $res->content()->create(
                    [
                        'res_text' => $resText,
                        'res_ver' => 1
                    ]
                );

                $processor = new Processor();

                switch ($resType) {
                    case 'Organization':
                        $org = $processor->generateOrganization($resText);
                        $org = $this->removeEmptyValues($org);
                        $processor->saveOrganization($res, $org);
                        break;
                    case 'Location':
                        $loc = $processor->generateLocation($resText);
                        $loc = $this->removeEmptyValues($loc);
                        $processor->saveLocation($res, $loc);
                        break;
                    case 'Practitioner':
                        $prac = $processor->generatePractitioner($resText);
                        $prac = $this->removeEmptyValues($prac);
                        $processor->savePractitioner($res, $prac);
                        break;
                    case 'Patient':
                        $pat = $processor->generatePatient($resText);
                        $pat = $this->removeEmptyValues($pat);
                        $processor->savePatient($res, $pat);
                        break;
                    case 'Encounter':
                        $enc = $processor->generateEncounter($resText);
                        $enc = $this->removeEmptyValues($enc);
                        $processor->saveEncounter($res, $enc);
                        break;
                    case 'Condition':
                        $cond = $processor->generateCondition($resText);
                        $cond = $this->removeEmptyValues($cond);
                        $processor->saveCondition($res, $cond);
                        break;
                    case 'Observation':
                        $obs = $processor->generateObservation($resText);
                        $obs = $this->removeEmptyValues($obs);
                        $processor->saveObservation($res, $obs);
                        break;
                    case 'Procedure':
                        $proc = $processor->generateProcedure($resText);
                        $proc = $this->removeEmptyValues($proc);
                        $processor->saveProcedure($res, $proc);
                        break;
                    case 'Medication':
                        $med = $processor->generateMedication($resText);
                        $med = $this->removeEmptyValues($med);
                        $processor->saveMedication($res, $med);
                        break;
                    case 'MedicationRequest':
                        $medReq = $processor->generateMedicationRequest($resText);
                        $medReq = $this->removeEmptyValues($medReq);
                        $processor->saveMedicationRequest($res, $medReq);
                        break;
                    case 'Composition':
                        $comp = $processor->generateComposition($resText);
                        $comp = $this->removeEmptyValues($comp);
                        $processor->saveComposition($res, $comp);
                        break;
                    case 'AllergyIntolerance':
                        $allergy = $processor->generateAllergyIntolerance($resText);
                        $allergy = $this->removeEmptyValues($allergy);
                        $processor->saveAllergyIntolerance($res, $allergy);
                        break;
                    case 'ClinicalImpression':
                        $clinicalImpression = $processor->generateClinicalImpression($resText);
                        $clinicalImpression = $this->removeEmptyValues($clinicalImpression);
                        $processor->saveClinicalImpression($res, $clinicalImpression);
                        break;
                    case 'ServiceRequest':
                        $serviceRequest = $processor->generateServiceRequest($resText);
                        $serviceRequest = $this->removeEmptyValues($serviceRequest);
                        $processor->saveServiceRequest($res, $serviceRequest);
                        break;
                    case 'MedicationStatement':
                        $medStatement = $processor->generateMedicationStatement($resText);
                        $medStatement = $this->removeEmptyValues($medStatement);
                        $processor->saveMedicationStatement($res, $medStatement);
                        break;
                    case 'QuestionnaireResponse':
                        $questionnaireResponse = $processor->generateQuestionnaireResponse($resText);
                        $questionnaireResponse = $this->removeEmptyValues($questionnaireResponse);
                        $processor->saveQuestionnaireResponse($res, $questionnaireResponse);
                        break;
                    default:
                        break;
                }

                $res->save();
                $res->refresh();
            }
        });
    }

    public function removeEmptyValues($array)
    {
        return array_filter($array, function ($value) {
            if (is_array($value)) {
                return !empty($this->removeEmptyValues($value));
            }
            return $value !== null && $value !== ""; //&& !(is_array($value) && empty($value));
        });
    }
}
