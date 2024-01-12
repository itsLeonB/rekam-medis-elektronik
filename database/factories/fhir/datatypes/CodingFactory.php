<?php

namespace Database\Factories\Fhir\Datatypes;

use App\Models\Fhir\BackboneElements\EncounterHospitalization;
use App\Models\Fhir\BackboneElements\EncounterParticipant;
use App\Models\Fhir\BackboneElements\PatientCommunication;
use App\Models\Fhir\BackboneElements\PatientContact;
use App\Models\Fhir\Resources\Encounter;
use App\Models\Fhir\Resources\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CodingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_selected' => fake()->boolean(),
        ];
    }

    public function dischargeDisposition(): self
    {
        $code = fake()->randomElement(EncounterHospitalization::DISCHARGE_DISPOSITION['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => EncounterHospitalization::DISCHARGE_DISPOSITION['binding']['valueset']['system'][$code],
            'code' => $code,
            'display' => EncounterHospitalization::DISCHARGE_DISPOSITION['binding']['valueset']['display'][$code],
        ]);
    }

    public function specialArrangement(): self
    {
        $code = fake()->randomElement(EncounterHospitalization::SPECIAL_ARRANGEMENT['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => EncounterHospitalization::SPECIAL_ARRANGEMENT['binding']['valueset']['system'],
            'code' => $code,
            'display' => EncounterHospitalization::SPECIAL_ARRANGEMENT['binding']['valueset']['display'][$code],
        ]);
    }

    public function dietPreference(): self
    {
        $code = fake()->randomElement(EncounterHospitalization::DIET_PREFERENCE['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => EncounterHospitalization::DIET_PREFERENCE['binding']['valueset']['system'],
            'code' => $code,
            'display' => EncounterHospitalization::DIET_PREFERENCE['binding']['valueset']['display'][$code],
        ]);
    }

    public function admitSource(): self
    {
        $code = fake()->randomElement(EncounterHospitalization::ADMIT_SOURCE['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => EncounterHospitalization::ADMIT_SOURCE['binding']['valueset']['system'],
            'code' => $code,
            'display' => EncounterHospitalization::ADMIT_SOURCE['binding']['valueset']['display'][$code],
        ]);
    }

    public function encounterParticipantType(): self
    {
        $code = fake()->randomElement(EncounterParticipant::TYPE['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => EncounterParticipant::TYPE['binding']['valueset']['system'][$code],
            'code' => $code,
            'display' => EncounterParticipant::TYPE['binding']['valueset']['display'][$code],
        ]);
    }

    public function encounterPriority(): self
    {
        $code = fake()->randomElement(Encounter::PRIORITY['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => Encounter::PRIORITY['binding']['valueset']['system'],
            'code' => $code,
            'display' => Encounter::PRIORITY['binding']['valueset']['display'][$code],
        ]);
    }

    public function encounterClass(): self
    {
        $code = fake()->randomElement(['AMB', 'IMP', 'EMER']);

        return $this->state(fn (array $attributes) => [
            'system' => Encounter::ENC_CLASS['binding']['valueset']['system'],
            'code' => $code,
            'display' => Encounter::ENC_CLASS['binding']['valueset']['display'][$code],
        ]);
    }

    public function encounterServiceType(): self
    {
        $code = fake()->randomElement([124, 177, 186, 88, 168, 218, 557, 221, 286, 263, 189, 221, 124, 286]);

        return $this->state(fn (array $attributes) => [
            'system' => Encounter::SERVICE_TYPE['binding']['valueset']['system'],
            'code' => $code,
            'display' => DB::table(Encounter::SERVICE_TYPE['binding']['valueset']['table'])
                ->where('code', $code)
                ->first()
                ->display,
        ]);
    }

    public function patientMaritalStatus(): self
    {
        $code = fake()->randomElement(Patient::MARITAL_STATUS['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => Patient::MARITAL_STATUS['binding']['valueset']['system'][$code],
            'code' => $code,
            'display' => Patient::MARITAL_STATUS['binding']['valueset']['display'][$code],
        ]);
    }

    public function patientContactRelationship(): self
    {
        $code = fake()->randomElement(PatientContact::RELATIONSHIP['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => PatientContact::RELATIONSHIP['binding']['valueset']['system'],
            'code' => $code,
            'display' => PatientContact::RELATIONSHIP['binding']['valueset']['display'][$code],
        ]);
    }

    public function patientCommunicationLanguage(): self
    {
        $code = DB::table(PatientCommunication::LANGUAGE['binding']['valueset']['table'])
            ->inRandomOrder()
            ->first()
            ->code;

        return $this->state(fn (array $attributes) => [
            'system' => PatientCommunication::LANGUAGE['binding']['valueset']['system'],
            'code' => $code,
            'display' => DB::table(PatientCommunication::LANGUAGE['binding']['valueset']['table'])
                ->where('code', $code)
                ->first()
                ->display,
        ]);
    }
}
