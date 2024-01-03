<?php

namespace App\Http\Requests\Fhir;

use App\Http\Requests\FhirRequest;
use App\Models\Fhir\BackboneElements\EncounterClassHistory;
use App\Models\Fhir\BackboneElements\EncounterLocation;
use App\Models\Fhir\BackboneElements\EncounterStatusHistory;
use App\Models\Fhir\Resources\Encounter;
use Illuminate\Validation\Rule;

class EncounterRequest extends FhirRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return array_merge(
            [
                'identifier' => 'required|array',
                'status' => ['required', Rule::in(Encounter::STATUS['binding']['valueset']['code'])],
                'statusHistory' => 'required|array',
                'statusHistory.*.status' => ['required', Rule::in(EncounterStatusHistory::STATUS['binding']['valueset']['code'])],
                'statusHistory.*.period' => 'required|array',
                'class' => 'required|array',
                'classHistory' => 'nullable|array',
                'classHistory.*.class' => 'required|array',
                'classHistory.*.period' => 'sometimes|array',
                'type' => 'nullable|array',
                'serviceType' => 'nullable|array',
                'priority' => 'nullable|array',
                'subject' => 'required|array',
                'episodeOfCare' => 'nullable|array',
                'basedOn' => 'nullable|array',
                'participant' => 'required|array',
                'participant.*.type' => 'nullable|array',
                'participant.*.period' => 'nullable|array',
                'participant.*.individual' => 'nullable|array',
                'appointment' => 'nullable|array',
                'period' => 'required|array',
                'length' => 'nullable|array',
                'reasonCode' => 'nullable|array',
                'reasonReference' => 'nullable|array',
                'diagnosis' => 'nullable|array',
                'diagnosis.*.condition' => 'sometimes|array',
                'diagnosis.*.use' => 'nullable|array',
                'diagnosis.*.rank' => 'nullable|integer|gte:1',
                'account' => 'nullable|array',
                'hospitalization' => 'nullable|array',
                'hospitalization.preAdmissionIdentifier' => 'nullable|array',
                'hospitalization.origin' => 'nullable|array',
                'hospitalization.admitSource' => 'nullable|array',
                'hospitalization.reAdmission' => 'nullable|boolean',
                'hospitalization.dietPreference' => 'nullable|array',
                'hospitalization.specialCourtesy' => 'nullable|array',
                'hospitalization.specialArrangement' => 'nullable|array',
                'hospitalization.destination' => 'nullable|array',
                'hospitalization.dischargeDisposition' => 'nullable|array',
                'location' => 'required|array',
                'location.*.location' => 'required|array',
                'location.*.status' => ['nullable', Rule::in(EncounterLocation::STATUS['binding']['valueset']['code'])],
                'location.*.physicalType' => 'nullable|array',
                'location.*.period' => 'nullable|array',
                'location.*.extension' => 'nullable|array',
                'serviceProvider' => 'required|array',
                'partOf' => 'nullable|array',
            ],
            $this->getIdentifierRules('identifier.*.'),
            $this->getPeriodRules('statusHistory.*.period.'),
            $this->getCodingRules('class.'),
            $this->getCodingRules('classHistory.*.class.'),
            $this->getPeriodRules('classHistory.*.period.'),
            $this->getCodeableConceptRules('type.*.'),
            $this->getCodeableConceptRules('serviceType.'),
            $this->getCodeableConceptRules('priority.'),
            $this->getReferenceRules('subject.'),
            $this->getReferenceRules('episodeOfCare.*.'),
            $this->getReferenceRules('basedOn.*.'),
            $this->getCodeableConceptRules('participant.*.type.*.'),
            $this->getPeriodRules('participant.*.period.'),
            $this->getReferenceRules('participant.*.individual.'),
            $this->getReferenceRules('appointment.*.'),
            $this->getPeriodRules('period.'),
            $this->getDurationRules('length.'),
            $this->getCodeableConceptRules('reasonCode.*.'),
            $this->getReferenceRules('reasonReference.*.'),
            $this->getReferenceRules('diagnosis.*.condition.'),
            $this->getCodeableConceptRules('diagnosis.*.use.'),
            $this->getReferenceRules('account.*.'),
            $this->getReferenceRules('hospitalization.preAdmissionIdentifier.'),
            $this->getReferenceRules('hospitalization.origin.'),
            $this->getCodeableConceptRules('hospitalization.admitSource.'),
            $this->getCodeableConceptRules('hospitalization.dietPreference.*.'),
            $this->getCodeableConceptRules('hospitalization.specialCourtesy.*.'),
            $this->getCodeableConceptRules('hospitalization.specialArrangement.*.'),
            $this->getReferenceRules('hospitalization.destination.'),
            $this->getCodeableConceptRules('hospitalization.dischargeDisposition.'),
            $this->getReferenceRules('location.*.location.'),
            $this->getCodeableConceptRules('location.*.physicalType.'),
            $this->getPeriodRules('location.*.period.'),
            $this->getComplexExtensionRules('location.*.extension.*.'),
            $this->getReferenceRules('serviceProvider.'),
            $this->getReferenceRules('partOf.'),
        );
    }
}
