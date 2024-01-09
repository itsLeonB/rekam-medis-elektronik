<?php

namespace App\Http\Requests;

use App\Models\Fhir\Datatypes\Address;
use App\Models\Fhir\Datatypes\Age;
use App\Models\Fhir\Datatypes\Attachment;
use App\Models\Fhir\Datatypes\ContactPoint;
use App\Models\Fhir\Datatypes\Duration;
use App\Models\Fhir\Datatypes\HumanName;
use App\Models\Fhir\Datatypes\Identifier;
use App\Models\Fhir\Datatypes\Narrative;
use App\Models\Fhir\Datatypes\Quantity;
use App\Models\Fhir\Datatypes\Reference;
use App\Models\Fhir\Datatypes\TimingRepeat;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FhirRequest extends FormRequest
{
    public function getIdentifierRules(string $prefix = null): array
    {
        return array_merge(
            [
                $prefix . 'use' => ['nullable', Rule::in(Identifier::USE['binding']['valueset']['code'])],
                $prefix . 'type' => 'nullable|array',
                $prefix . 'system' => 'nullable|string',
                $prefix . 'value' => 'nullable|string',
                $prefix . 'period' => 'nullable|array',
                $prefix . 'assigner' => 'nullable|array',
            ],
            $this->getCodeableConceptRules($prefix . 'type.'),
            $this->getPeriodRules($prefix . 'period.'),
            $this->getReferenceRules($prefix . 'assigner.')
        );
    }

    public function getReferenceRules(string $prefix = null): array
    {
        return [
            $prefix . 'reference' => 'nullable|string',
            $prefix . 'type' => ['nullable', Rule::exists(Reference::TYPE['binding']['valueset']['table'], 'code')],
            $prefix . 'display' => 'nullable|string',
        ];
    }

    public function getAnnotationRules(string $prefix = null): array
    {
        return array_merge(
            [
                $prefix . 'authorReference' => 'nullable|array',
                $prefix . 'authorString' => 'nullable|string',
                $prefix . 'time' => 'nullable|date',
                $prefix . 'text' => 'sometimes|string'
            ],
            $this->getReferenceRules($prefix . 'authorReference.')
        );
    }

    public function getAttachmentRules(string $prefix = null): array
    {
        return [
            $prefix . 'contentType' => ['nullable', Rule::exists(Attachment::CONTENT_TYPE['binding']['valueset']['table'], 'code')],
            $prefix . 'language' => ['nullable', Rule::exists(Attachment::LANGUAGE['binding']['valueset']['table'], 'code')],
            $prefix . 'data' => 'nullable|string',
            $prefix . 'url' => 'nullable|string',
            $prefix . 'size' => 'nullable|integer|gte:0',
            $prefix . 'hash' => 'nullable|string',
            $prefix . 'title' => 'nullable|string',
            $prefix . 'creation' => 'nullable|date',
        ];
    }

    public function getContactPointRules(string $prefix = null): array
    {
        return array_merge(
            [
                $prefix . 'system' => ['nullable', Rule::in(ContactPoint::SYSTEM['binding']['valueset']['code'])],
                $prefix . 'value' => 'nullable|string',
                $prefix . 'use' => ['nullable', Rule::in(ContactPoint::USE['binding']['valueset']['code'])],
                $prefix . 'rank' => 'nullable|integer|gte:1',
                $prefix . 'period' => 'nullable|array',
            ],
            $this->getPeriodRules($prefix . 'period.')
        );
    }

    public function getDurationRules(string $prefix = null): array
    {
        return [
            $prefix . 'value' => 'nullable|numeric',
            $prefix . 'comparator' => ['nullable', Rule::in(Duration::COMPARATOR['binding']['valueset'])],
            $prefix . 'unit' => 'nullable|string',
            $prefix . 'system' => 'nullable|string',
            $prefix . 'code' => 'nullable|string',
        ];
    }

    public function getDosageRules(string $prefix = null): array
    {
        return array_merge(
            [
                $prefix . 'sequence' => 'nullable|integer',
                $prefix . 'text' => 'nullable|string',
                $prefix . 'additionalInstruction' => 'nullable|array',
                $prefix . 'patientInstruction' => 'nullable|string',
                $prefix . 'timing' => 'nullable|array',
                $prefix . 'asNeededBoolean' => 'nullable|boolean',
                $prefix . 'asNeededCodeableConcept' => 'nullable|array',
                $prefix . 'site' => 'nullable|array',
                $prefix . 'route' => 'nullable|array',
                $prefix . 'method' => 'nullable|array',
                $prefix . 'doseAndRate' => 'nullable|array',
                $prefix . 'doseAndRate.*.type' => 'nullable|array',
                $prefix . 'doseAndRate.*.doseRange' => 'nullable|array',
                $prefix . 'doseAndRate.*.doseQuantity' => 'nullable|array',
                $prefix . 'doseAndRate.*.rateRatio' => 'nullable|array',
                $prefix . 'doseAndRate.*.rateRange' => 'nullable|array',
                $prefix . 'doseAndRate.*.rateQuantity' => 'nullable|array',
                $prefix . 'maxDosePerPeriod' => 'nullable|array',
                $prefix . 'maxDosePerAdministration' => 'nullable|array',
                $prefix . 'maxDosePerLifetime' => 'nullable|array'
            ],
            $this->getCodeableConceptRules($prefix . 'additionalInstruction.*.'),
            $this->getTimingRules($prefix . 'timing.'),
            $this->getCodeableConceptRules($prefix . 'asNeededCodeableConcept.'),
            $this->getCodeableConceptRules($prefix . 'site.'),
            $this->getCodeableConceptRules($prefix . 'route.'),
            $this->getCodeableConceptRules($prefix . 'method.'),
            $this->getCodeableConceptRules($prefix . 'doseAndRate.*.type.'),
            $this->getRangeRules($prefix . 'doseAndRate.*.doseRange.'),
            $this->getSimpleQuantityRules($prefix . 'doseAndRate.*.doseQuantity.'),
            $this->getRatioRules($prefix . 'doseAndRate.*.rateRatio.'),
            $this->getRangeRules($prefix . 'doseAndRate.*.rateRange.'),
            $this->getSimpleQuantityRules($prefix . 'doseAndRate.*.rateQuantity.'),
            $this->getRatioRules($prefix . 'maxDosePerPeriod.'),
            $this->getSimpleQuantityRules($prefix . 'maxDosePerAdministration.'),
            $this->getSimpleQuantityRules($prefix . 'maxDosePerLifetime.')
        );
    }

    public function getCodingRules(string $prefix = null): array
    {
        return [
            $prefix . 'system' => 'nullable|string',
            $prefix . 'version' => 'nullable|string',
            $prefix . 'code' => 'nullable|string',
            $prefix . 'display' => 'nullable|string',
            $prefix . 'userSelected' => 'nullable|boolean',
        ];
    }

    public function getCodeableConceptRules(string $prefix = null): array
    {
        return array_merge(
            [
                $prefix . 'coding' => 'nullable|array',
                $prefix . 'text' => 'nullable|string',
            ],
            $this->getCodingRules($prefix . 'coding.')
        );
    }

    public function getAgeRules(string $prefix = null): array
    {
        return [
            $prefix . 'value' => 'nullable|numeric',
            $prefix . 'comparator' => ['nullable', Rule::in(Age::COMPARATOR['binding']['valueset'])],
            $prefix . 'unit' => 'nullable|string',
            $prefix . 'system' => 'nullable|string',
            $prefix . 'code' => 'nullable|string',
        ];
    }

    public function getAddressRules(string $prefix = null): array
    {
        return array_merge(
            [
                $prefix . 'use' => ['nullable', Rule::in(Address::USE['binding']['valueset']['code'])],
                $prefix . 'type' => ['nullable', Rule::in(Address::TYPE['binding']['valueset']['code'])],
                $prefix . 'text' => 'nullable|string',
                $prefix . 'line' => 'nullable|array',
                $prefix . 'line.*' => 'sometimes|string',
                $prefix . 'city' => 'nullable|string',
                $prefix . 'district' => 'nullable|string',
                $prefix . 'state' => 'nullable|string',
                $prefix . 'postalCode' => 'nullable|string',
                $prefix . 'country' => ['nullable', Rule::exists(Address::COUNTRY['binding']['valueset']['table'], 'code')],
                $prefix . 'extension' => 'nullable|array',
            ],
            $this->getComplexExtensionRules($prefix . 'extension.*.')
        );
    }

    public function getComplexExtensionRules(string $prefix = null): array
    {
        return array_merge(
            [
                $prefix . 'url' => 'sometimes|string',
                $prefix . 'extension' => 'nullable|array',
            ],
            $this->getExtensionRules($prefix . 'extension.*.')
        );
    }

    public function getHumanNameRules(string $prefix = null): array
    {
        return array_merge(
            [
                $prefix . 'use' => ['nullable', Rule::in(HumanName::USE['binding']['valueset']['code'])],
                $prefix . 'text' => 'nullable|string',
                $prefix . 'family' => 'nullable|string',
                $prefix . 'given' => 'nullable|array',
                $prefix . 'given.*' => 'sometimes|string',
                $prefix . 'prefix' => 'nullable|array',
                $prefix . 'prefix.*' => 'sometimes|string',
                $prefix . 'suffix' => 'nullable|array',
                $prefix . 'suffix.*' => 'sometimes|string',
                $prefix . 'period' => 'nullable|array',
            ],
            $this->getPeriodRules($prefix . 'period.')
        );
    }

    public function getQuantityRules(string $prefix = null): array
    {
        return [
            $prefix . 'value' => 'nullable|numeric',
            $prefix . 'comparator' => ['nullable', Rule::in(Quantity::COMPARATOR['binding']['valueset'])],
            $prefix . 'unit' => 'nullable|string',
            $prefix . 'system' => 'nullable|string',
            $prefix . 'code' => 'nullable|string',
        ];
    }

    public function getRangeRules(string $prefix = null): array
    {
        return array_merge(
            [
                $prefix . 'low' => 'nullable|array',
                $prefix . 'high' => 'nullable|array',
            ],
            $this->getSimpleQuantityRules($prefix . 'low.'),
            $this->getSimpleQuantityRules($prefix . 'high.')
        );
    }

    public function getRatioRules(string $prefix = null): array
    {
        return array(
            [
                $prefix . 'numerator' => 'nullable|array',
                $prefix . 'denominator' => 'nullable|array',
            ],
            $this->getQuantityRules($prefix . 'numerator.'),
            $this->getQuantityRules($prefix . 'denominator.')
        );
    }

    public function getSampledDataRules(string $prefix = null): array
    {
        return array_merge(
            [
                $prefix . 'origin' => 'sometimes|array',
                $prefix . 'period' => 'sometimes|numeric',
                $prefix . 'factor' => 'nullable|numeric',
                $prefix . 'lowerLimit' => 'nullable|numeric',
                $prefix . 'upperLimit' => 'nullable|numeric',
                $prefix . 'dimensions' => 'sometimes|integer|gte:1',
                $prefix . 'data' => 'nullable|string',
            ],
            $this->getSimpleQuantityRules($prefix . 'origin.')
        );
    }

    public function getTimingRules(string $prefix = null): array
    {
        return array_merge(
            [
                $prefix . 'event' => 'nullable|array',
                $prefix . 'event.*' => 'sometimes|date',
                $prefix . 'repeat' => 'nullable|array',
                $prefix . 'repeat.boundsDuration' => 'nullable|array',
                $prefix . 'repeat.boundsRange' => 'nullable|array',
                $prefix . 'repeat.boundsPeriod' => 'nullable|array',
                $prefix . 'repeat.count' => 'nullable|integer|gte:1',
                $prefix . 'repeat.countMax' => 'nullable|integer|gte:1',
                $prefix . 'repeat.duration' => 'nullable|numeric',
                $prefix . 'repeat.durationMax' => 'nullable|numeric',
                $prefix . 'repeat.durationUnit' => 'nullable|string',
                $prefix . 'repeat.frequency' => 'nullable|integer|gte:1',
                $prefix . 'repeat.frequencyMax' => 'nullable|integer|gte:1',
                $prefix . 'repeat.period' => 'nullable|numeric',
                $prefix . 'repeat.periodMax' => 'nullable|numeric',
                $prefix . 'repeat.periodUnit' => 'nullable|string',
                $prefix . 'repeat.dayOfWeek' => 'nullable|array',
                $prefix . 'repeat.dayOfWeek.*' => ['sometimes', Rule::in(TimingRepeat::DAY_OF_WEEK['binding']['valueset']['code'])],
                $prefix . 'repeat.timeOfDay' => 'nullable|array',
                $prefix . 'repeat.timeOfDay.*' => 'sometimes|date_format:H:i:s',
                $prefix . 'repeat.when' => 'nullable|array',
                $prefix . 'repeat.when.*' => ['sometimes', Rule::in(TimingRepeat::WHEN['binding']['valueset']['code'])],
                $prefix . 'repeat.offset' => 'nullable|integer|gte:0',
                $prefix . 'code' => 'nullable|array',
            ],
            $this->getDurationRules($prefix . 'repeat.boundsDuration.'),
            $this->getRangeRules($prefix . 'repeat.boundsRange.'),
            $this->getPeriodRules($prefix . 'repeat.boundsPeriod.'),
            $this->getCodeableConceptRules($prefix . 'code.')
        );
    }

    public function getPeriodRules(string $prefix = null): array
    {
        return [
            $prefix . 'start' => 'nullable|date',
            $prefix . 'end' => 'nullable|date',
        ];
    }

    public function getSimpleQuantityRules(string $prefix = null): array
    {
        return [
            $prefix . 'value' => 'nullable|numeric',
            $prefix . 'unit' => 'nullable|string',
            $prefix . 'system' => 'nullable|string',
            $prefix . 'code' => 'nullable|string',
        ];
    }

    public function getNarrativeRules(string $prefix = null): array
    {
        return [
            $prefix . 'status' => ['sometimes', Rule::in(Narrative::STATUS['binding']['valueset']['code'])],
            $prefix . 'div' => 'sometimes|string'
        ];
    }

    public function getExtensionRules(string $prefix = null): array
    {
        return [
            $prefix . 'url' => 'sometimes|string',
            $prefix . 'valueBase64Binary' => 'nullable|string',
            $prefix . 'valueBoolean' => 'nullable|boolean',
            $prefix . 'valueCanonical' => 'nullable|string',
            $prefix . 'valueCode' => 'nullable|string',
            $prefix . 'valueDate' => 'nullable|date',
            $prefix . 'valueDateTime' => 'nullable|date',
            $prefix . 'valueDecimal' => 'nullable|numeric',
            $prefix . 'valueId' => 'nullable|string',
            $prefix . 'valueInstant' => 'nullable|date',
            $prefix . 'valueInteger' => 'nullable|integer',
            $prefix . 'valueMarkdown' => 'nullable|string',
            $prefix . 'valueOid' => 'nullable|string',
            $prefix . 'valuePositiveInt' => 'nullable|integer|gte:1',
            $prefix . 'valueString' => 'nullable|string',
            $prefix . 'valueTime' => 'nullable|date_format:H:i:s',
            $prefix . 'valueUnsignedInt' => 'nullable|integer|gte:0',
            $prefix . 'valueUri' => 'nullable|string',
            $prefix . 'valueUrl' => 'nullable|string',
            $prefix . 'valueUuid' => 'nullable|string',
            $prefix . 'valueAddress' => 'nullable|array',
            $prefix . 'valueAge' => 'nullable|array',
            $prefix . 'valueAnnotation' => 'nullable|array',
            $prefix . 'valueAttachment' => 'nullable|array',
            $prefix . 'valueCodeableConcept' => 'nullable|array',
            $prefix . 'valueCoding' => 'nullable|array',
            $prefix . 'valueContactPoint' => 'nullable|array',
            $prefix . 'valueCount' => 'nullable|array',
            $prefix . 'valueDistance' => 'nullable|array',
            $prefix . 'valueDuration' => 'nullable|array',
            $prefix . 'valueHumanName' => 'nullable|array',
            $prefix . 'valueIdentifier' => 'nullable|array',
            $prefix . 'valueMoney' => 'nullable|array',
            $prefix . 'valuePeriod' => 'nullable|array',
            $prefix . 'valueQuantity' => 'nullable|array',
            $prefix . 'valueRange' => 'nullable|array',
            $prefix . 'valueRatio' => 'nullable|array',
            $prefix . 'valueSampledData' => 'nullable|array',
            $prefix . 'valueSignature' => 'nullable|array',
            $prefix . 'valueTiming' => 'nullable|array',
            $prefix . 'valueContactDetail' => 'nullable|array',
            $prefix . 'valueContributor' => 'nullable|array',
            $prefix . 'valueDataRequirement' => 'nullable|array',
            $prefix . 'valueExpression' => 'nullable|array',
            $prefix . 'valueParameterDefinition' => 'nullable|array',
            $prefix . 'valueRelatedArtifact' => 'nullable|array',
            $prefix . 'valueTriggerDefinition' => 'nullable|array',
            $prefix . 'valueUsageContext' => 'nullable|array',
            $prefix . 'valueDosage' => 'nullable|array',
            $prefix . 'valueMeta' => 'nullable|array',
            $prefix . 'valueReference' => 'nullable|array',
        ];
    }
}
