<?php

namespace Database\Seeders;

use App\Fhir\Processor;
use App\Models\Fhir\BackboneElements\EncounterClassHistory;
use App\Models\Fhir\BackboneElements\EncounterHospitalization;
use App\Models\Fhir\BackboneElements\EncounterLocation;
use App\Models\Fhir\BackboneElements\EncounterParticipant;
use App\Models\Fhir\BackboneElements\EncounterStatusHistory;
use App\Models\Fhir\BackboneElements\PatientCommunication;
use App\Models\Fhir\BackboneElements\PatientContact;
use App\Models\Fhir\Datatypes\Address;
use App\Models\Fhir\Datatypes\CodeableConcept;
use App\Models\Fhir\Datatypes\Coding;
use App\Models\Fhir\Datatypes\ComplexExtension;
use App\Models\Fhir\Datatypes\ContactPoint;
use App\Models\Fhir\Datatypes\Extension;
use App\Models\Fhir\Datatypes\HumanName;
use App\Models\Fhir\Datatypes\Identifier;
use App\Models\Fhir\Datatypes\Period;
use App\Models\Fhir\Datatypes\Reference;
use App\Models\Fhir\Resource;
use App\Models\Fhir\Resources\Encounter;
use App\Models\Fhir\Resources\Patient;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $processor = new Processor();

            $files = Storage::disk('onboarding-resource')->files();

            foreach ($files as $f) {
                $resText = Storage::disk('onboarding-resource')->get($f);
                list($resType, $satusehatId) = explode('-', $f, 2);
                list($satusehatId, $ext) = explode('.', $satusehatId, 2);

                switch ($resType) {
                    case 'Organization':
                        $org = Resource::create(
                            [
                                'satusehat_id' => config('app.organization_id'),
                                'res_type' => $resType
                            ]
                        );

                        $org->content()->create(
                            [
                                'res_text' => $resText,
                                'res_ver' => 1
                            ]
                        );

                        $resText = json_decode($resText, true);
                        $orgData = $processor->generateOrganization($resText);
                        $orgData = $this->removeEmptyValues($orgData);
                        $processor->saveOrganization($org, $orgData);
                        $org->save();

                        break;
                    case 'Location':
                        $loc = Resource::create(
                            [
                                'satusehat_id' => 'mock-location',
                                'res_type' => $resType
                            ]
                        );

                        $loc->content()->create(
                            [
                                'res_text' => $resText,
                                'res_ver' => 1
                            ]
                        );

                        $resText = json_decode($resText, true);
                        $locData = $processor->generateLocation($resText);
                        $locData = $this->removeEmptyValues($locData);
                        $processor->saveLocation($loc, $locData);
                        $loc->save();

                        break;
                    case 'Practitioner':
                        $prac = Resource::create(
                            [
                                'satusehat_id' => 'rsum',
                                'res_type' => $resType
                            ]
                        );

                        $prac->content()->create(
                            [
                                'res_text' => $resText,
                                'res_ver' => 1
                            ]
                        );

                        $resText = json_decode($resText, true);
                        $pracData = $processor->generatePractitioner($resText);
                        $pracData = $this->removeEmptyValues($pracData);
                        $processor->savePractitioner($prac, $pracData);
                        $prac->save();

                        break;
                }
            }

            for ($i = 0; $i < 20; $i++) {
                $this->dummyEncounter();
            }
        });
    }

    private function dummyEncounter()
    {
        $patient = $this->dummyPatient();
        $practitioner = Resource::where('res_type', 'Practitioner')->inRandomOrder()->first();

        $encounter = Encounter::factory()->create();

        for ($i = 0, $iMax = rand(1, 3); $i < $iMax; $i++) {
            EncounterStatusHistory::factory()
                ->for($encounter, 'encounter')
                ->has(Period::factory(), 'period')
                ->create();
        }

        $this->fakeCodeableConcept($encounter, 'encounterClass', 'class');

        for ($i = 0, $iMax = rand(1, 3); $i < $iMax; $i++) {
            $encClassHistory = EncounterClassHistory::factory()
                ->for($encounter, 'encounter')
                ->has(Period::factory(), 'period')
                ->create();
            $this->fakeCodeableConcept($encClassHistory, 'encounterClass', 'class');
        }

        $this->fakeCodeableConcept($encounter, 'encounterPriority', 'priority');

        Reference::factory()->for($encounter, 'referenceable')->create([
            'reference' => 'Patient/' . $patient->resource->satusehat_id,
            'attr_type' => 'subject'
        ]);

        for ($i = 0, $iMax = rand(1, 3); $i < $iMax; $i++) {
            $encParticipant = EncounterParticipant::factory()
                ->for($encounter, 'encounter')
                ->has(Period::factory(), 'period')
                ->create();
            $this->fakeCodeableConcept($encParticipant, 'encounterParticipantType', 'type');
            Reference::factory()->for($encParticipant, 'referenceable')->create([
                'reference' => 'Practitioner/' . $practitioner->satusehat_id,
                'attr_type' => 'individual'
            ]);
        }

        Period::factory()->for($encounter, 'periodable')->create();

        $encHosp = EncounterHospitalization::factory()->for($encounter, 'encounter')->create();
        $this->fakeCodeableConcept($encHosp, 'admitSource', 'admitSource');
        $this->fakeCodeableConcept($encHosp, 'dietPreference', 'dietPreference');
        $this->fakeCodeableConcept($encHosp, 'specialArrangement', 'specialArrangement');
        $this->fakeCodeableConcept($encHosp, 'dischargeDisposition', 'dischargeDisposition');

        $locId = Resource::where('res_type', 'Location')->inRandomOrder()->first()->satusehat_id;
        $loc = EncounterLocation::factory()->for($encounter, 'encounter')->create();
        Reference::factory()->for($loc, 'referenceable')->create([
            'reference' => 'Location/' . $locId,
            'attr_type' => 'location'
        ]);

        Reference::factory()->for($encounter, 'referenceable')->create([
            'reference' => 'Organization/' . config('app.organization_id'),
            'attr_type' => 'serviceProvider'
        ]);
    }

    private function dummyPatient()
    {
        $patient = Patient::factory()->create();

        $isChild = [true, false];
        $isChild = fake()->randomElement($isChild);

        Identifier::factory()->rekamMedis()->for($patient, 'identifiable')->create(['attr_type' => 'identifier']);

        if ($isChild) {
            Identifier::factory()->nikIbu()->for($patient, 'identifiable')->create(['attr_type' => 'identifier']);
        } else {
            Identifier::factory()->nik()->for($patient, 'identifiable')->create(['attr_type' => 'identifier']);
            Identifier::factory()->bpjs()->for($patient, 'identifiable')->create(['attr_type' => 'identifier']);
            Identifier::factory()->paspor()->for($patient, 'identifiable')->create(['attr_type' => 'identifier']);
            Identifier::factory()->kk()->for($patient, 'identifiable')->create(['attr_type' => 'identifier']);
        }

        HumanName::factory()->for($patient, 'humanNameable')->create(['attr_type' => 'name']);
        $this->fakeTelecom($patient);
        $this->fakeAddress($patient);
        $this->fakeCodeableConcept($patient, 'patientMaritalStatus', 'maritalStatus');

        for ($i = 0; $i < 2; $i++) {
            $contact = PatientContact::factory()->for($patient, 'patient')->create();
            $this->fakeCodeableConcept($contact, 'patientContactRelationship', 'relationship');
            HumanName::factory()->for($contact, 'humanNameable')->create(['attr_type' => 'name']);
            $this->fakeTelecom($contact);
            $this->fakeAddress($contact);
        }

        for ($i = 0; $i < 2; $i++) {
            $communication = PatientCommunication::factory()->for($patient, 'patient')->create();
            $this->fakeCodeableConcept($communication, 'patientCommunicationLanguage', 'language');
        }

        return $patient;
    }

    private function fakeCodeableConcept($parent, $attribute, $type)
    {
        CodeableConcept::factory()
            ->for($parent, 'codeable')
            ->has(Coding::factory()->$attribute(), 'coding')
            ->create(['attr_type' => $type]);
    }

    private function fakeComplexExtension($parent, $attribute, $type)
    {
        $administrativeCode = ComplexExtension::factory()->$attribute()->for($parent, 'complexExtendable')->create(['attr_type' => $type]);
        foreach ($administrativeCode->exts as $ext) {
            Extension::factory()->$ext()->for($administrativeCode, 'extendable')->create(['attr_type' => 'extension']);
        }
    }

    private function fakeTelecom($parent)
    {
        ContactPoint::factory()->phone()->for($parent, 'contactPointable')->create([
            'attr_type' => 'telecom',
            'rank' => 1
        ]);
        ContactPoint::factory()->email()->for($parent, 'contactPointable')->create([
            'attr_type' => 'telecom',
            'rank' => 2
        ]);
        ContactPoint::factory()->url()->for($parent, 'contactPointable')->create([
            'attr_type' => 'telecom',
            'rank' => 3
        ]);
    }

    private function fakeAddress($parent)
    {
        for ($i = 0; $i < 2; $i++) {
            $address = Address::factory()->for($parent, 'addressable')->create(['attr_type' => 'address']);
            $this->fakeComplexExtension($address, 'administrativeCode', 'administrativeCode');
            $this->fakeComplexExtension($address, 'geolocation', 'geolocation');
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
