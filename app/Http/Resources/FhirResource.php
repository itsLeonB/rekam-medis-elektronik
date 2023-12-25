<?php

namespace App\Http\Resources;

use App\Fhir\Codesystems;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Resources\Json\JsonResource;

class FhirResource extends JsonResource
{
    public function createDurationResource($duration)
    {
        if (!empty($duration)) {
            return [
                'value' => $duration->value,
                'comparator' => $duration->comparator,
                'unit' => $duration->unit,
                'system' => $duration->system,
                'code' => $duration->code,
            ];
        } else {
            return null;
        }
    }

    public function createRangeResource($range)
    {
        if (!empty($range)) {
            return [
                'low' => $this->createSimpleQuantityResource($range->low),
                'high' => $this->createSimpleQuantityResource($range->high),
            ];
        } else {
            return null;
        }
    }

    public function createSimpleQuantityResource($simpleQuantity)
    {
        if (!empty($simpleQuantity)) {
            return [
                'value' => $simpleQuantity->value,
                'unit' => $simpleQuantity->unit,
                'system' => $simpleQuantity->system,
                'code' => $simpleQuantity->code,
            ];
        } else {
            return null;
        }
    }

    public function createTimingRepeatResource($repeat)
    {
        if (!empty($repeat)) {
            return [
                'boundsDuration' => $this->createDurationResource($repeat->boundsDuration),
                'boundsRange' => $this->createRangeResource($repeat->boundsRange),
                'boundsPeriod' => $this->createPeriodResource($repeat->boundsPeriod),
                'count' => $repeat->count,
                'countMax' => $repeat->count_max,
                'duration' => $repeat->duration,
                'durationMax' => $repeat->duration_max,
                'durationUnit' => $repeat->duration_unit,
                'frequency' => $repeat->frequency,
                'frequencyMax' => $repeat->frequency_max,
                'period' => $repeat->period,
                'periodMax' => $repeat->period_max,
                'periodUnit' => $repeat->period_unit,
                'dayOfWeek' => $repeat->day_of_week,
                'timeOfDay' => $repeat->time_of_day,
                'when' => $repeat->when,
                'offset' => $repeat->offset,
            ];
        } else {
            return null;
        }
    }

    public function createTimingResource($timing)
    {
        if (!empty($timing)) {
            return [
                'event' => $timing->event,
                'repeat' => $this->createTimingRepeatResource($timing->repeat),
                'code' => $this->createCodeableConceptResource($timing->code),
            ];
        } else {
            return null;
        }
    }

    public function createDoseAndRateResource($doseAndRate)
    {
        if (!empty($doseAndRate)) {
            return [
                'type' => $this->createCodeableConceptResource($doseAndRate->type),
                'doseRange' => $this->createRangeResource($doseAndRate->doseRange),
                'doseQuantity' => $this->createSimpleQuantityResource($doseAndRate->doseQuantity),
                'rateRatio' => $this->createRatioResource($doseAndRate->rateRatio),
                'rateRange' => $this->createRangeResource($doseAndRate->rateRange),
                'rateQuantity' => $this->createSimpleQuantityResource($doseAndRate->rateQuantity),
            ];
        } else {
            return null;
        }
    }

    public function createQuantityResource($quantity)
    {
        if (!empty($quantity)) {
            return [
                'value' => $quantity->value,
                'comparator' => $quantity->comparator,
                'unit' => $quantity->unit,
                'system' => $quantity->system,
                'code' => $quantity->code,
            ];
        } else {
            return null;
        }
    }

    public function createRatioResource($ratio)
    {
        if (!empty($ratio)) {
            return [
                'numerator' => $this->createQuantityResource($ratio->numerator),
                'denominator' => $this->createQuantityResource($ratio->denominator),
            ];
        } else {
            return null;
        }
    }

    public function createDosageResource($dosage)
    {
        if (!empty($dosage)) {
            return [
                'sequence' => $dosage->sequence,
                'text' => $dosage->text,
                'additionalInstruction' => $this->createMany($dosage->additionalInstruction, 'createCodeableConceptResource'),
                'patientInstruction' => $dosage->patientInstruction,
                'timing' => $this->createTimingResource($dosage->timing),
                'asNeededBoolean' => $dosage->asNeededBoolean,
                'asNeededCodeableConcept' => $this->createCodeableConceptResource($dosage->asNeededCodeableConcept),
                'site' => $this->createCodeableConceptResource($dosage->site),
                'route' => $this->createCodeableConceptResource($dosage->route),
                'method' => $this->createCodeableConceptResource($dosage->method),
                'doseAndRate' => $this->createMany($dosage->doseAndRate, 'createDoseAndRateResource'),
                'maxDosePerPeriod' => $this->createRatioResource($dosage->maxDosePerPeriod),
                'maxDosePerAdministration' => $this->createSimpleQuantityResource($dosage->maxDosePerAdministration),
                'maxDosePerLifetime' => $this->createSimpleQuantityResource($dosage->maxDosePerLifetime),
            ];
        } else {
            return null;
        }
    }

    public function createContactPointResource($contactPoint)
    {
        if (!empty($contactPoint)) {
            return [
                'system' => $contactPoint->system,
                'value' => $contactPoint->value,
                'use' => $contactPoint->use,
                'rank' => $contactPoint->rank,
                'period' => $this->createPeriodResource($contactPoint->period),
            ];
        } else {
            return null;
        }
    }

    public function createCodeableConceptResource($codeableConcept)
    {
        if (!empty($codeableConcept)) {
            return [
                'coding' => $this->createMany($codeableConcept->coding, 'createCodingResource'),
                'text' => $codeableConcept->text,
            ];
        } else {
            return null;
        }
    }

    public function createMany($data, $method)
    {
        if (!empty($data)) {
            $arr = [];
            foreach ($data as $d) {
                $arr[] = $this->$method($d);
            }
            return $arr;
        } else {
            return null;
        }
    }

    public function createCodingResource($coding)
    {
        if (!empty($coding)) {
            return [
                'system' => $coding->system,
                'version' => $coding->version,
                'code' => $coding->code,
                'display' => $coding->display,
                'userSelected' => $coding->user_selected,
            ];
        } else {
            return null;
        }
    }

    public function createAttachmentResource($attachment)
    {
        if (!empty($attachment)) {
            return [
                'contentType' => $attachment->content_type,
                'language' => $attachment->language,
                'data' => $attachment->data,
                'url' => $attachment->url,
                'size' => $attachment->size,
                'hash' => $attachment->hash,
                'title' => $attachment->title,
                'creation' => $this->parseDateTime($attachment->creation),
            ];
        } else {
            return null;
        }
    }

    public function createIdentifierResource($identifier)
    {
        if (!empty($identifier)) {
            return [
                'use' => $identifier->use,
                'type' => $this->createCodeableConceptResource($identifier->type),
                'system' => $identifier->system,
                'value' => $identifier->value,
                'period' => $this->createPeriodResource($identifier->period),
                'assigner' => $this->createReferenceResource($identifier->assigner),
            ];
        } else {
            return null;
        }
    }

    public function createReferenceResource($reference)
    {
        if (!empty($reference)) {
            return [
                'reference' => $reference->reference,
                'type' => $reference->type,
                'identifier' => $this->createIdentifierResource($reference->identifier),
                'display' => $reference->display,
            ];
        } else {
            return null;
        }
    }

    public function createAnnotationResource($annotation)
    {
        if (!empty($annotation)) {
            return [
                'authorString' => $annotation->author_string,
                'authorReference' => $this->createReferenceResource($annotation->authorReference),
                'time' => $this->parseDateTime($annotation->time),
                'text' => $annotation->text,
            ];
        } else {
            return null;
        }
    }

    public function createAgeResource($age)
    {
        if (!empty($age)) {
            return [
                'value' => $age->value,
                'comparator' => $age->comparator,
                'unit' => $age->unit,
                'system' => $age->system,
                'code' => $age->code,
            ];
        } else {
            return null;
        }
    }

    public function createAddressResource($address)
    {
        if (!empty($address)) {
            return [
                'use' => $address->use,
                'type' => $address->type,
                'text' => $address->text,
                'line' => $address->line,
                'city' => $address->city,
                'district' => $address->district,
                'state' => $address->state,
                'postalCode' => $address->postalCode,
                'country' => $address->country,
                'period' => $this->createPeriodResource($address->period),
                'extension' => [
                    $this->createComplexExtensionResource($address->administrativeCode),
                    $this->createComplexExtensionResource($address->geolocation),
                ]
            ];
        } else {
            return null;
        }
    }

    public function createHumanNameResource($humanName)
    {
        if (!empty($humanName)) {
            return [
                'use' => $humanName->use,
                'text' => $humanName->text,
                'family' => $humanName->family,
                'given' => $humanName->given,
                'prefix' => $humanName->prefix,
                'suffix' => $humanName->suffix,
                'period' => $this->createPeriodResource($humanName->period),
            ];
        } else {
            return null;
        }
    }

    public function createComplexExtensionResource($complexExtension)
    {
        if (!empty($complexExtension)) {
            if (!empty($complexExtension->exts)) {
                $extension = [];
                foreach ($complexExtension->exts as $ext) {
                    $extension[] = $this->createExtensionResource($complexExtension->extension($ext)->first());
                }
                return [
                    'url' => $complexExtension->url,
                    'extension' => $extension,
                ];
            } else {
                $extension = null;
            }
        }
    }

    public function createNarrativeResource($narrative)
    {
        if (!empty($narrative)) {
            return [
                'status' => $narrative->status,
                'div' => $narrative->div,
            ];
        } else {
            return null;
        }
    }

    public function createSampledDataResource($sampledData)
    {
        if (!empty($sampledData)) {
            return [
                'origin' => $this->createSimpleQuantityResource($sampledData->origin),
                'period' => $sampledData->period,
                'factor' => $sampledData->factor,
                'lowerLimit' => $sampledData->lowerLimit,
                'upperLimit' => $sampledData->upperLimit,
                'dimensions' => $sampledData->dimensions,
                'data' => $sampledData->data,
            ];
        } else {
            return null;
        }
    }

    public function createExtensionResource($extension)
    {
        if (!empty($extension)) {
            return [
                'url' => $extension->url,
                'valueBase64Binary' => $extension->value_base_64_binary,
                'valueBoolean' => $extension->value_boolean,
                'valueCanonical' => $extension->value_canonical,
                'valueCode' => $extension->value_code,
                'valueDate' => $extension->value_date,
                'valueDateTime' => $this->parseDateTime($extension->value_date_time),
                'valueDecimal' => $extension->value_decimal,
                'valueId' => $extension->value_id,
                'valueInstant' => $this->parseDateTime($extension->value_instant),
                'valueInteger' => $extension->value_integer,
                'valueMarkdown' => $extension->value_markdown,
                'valueOid' => $extension->value_oid,
                'valuePositiveInt' => $extension->value_positive_int,
                'valueString' => $extension->value_string,
                'valueTime' => $extension->value_time,
                'valueUnsignedInt' => $extension->value_unsigned_int,
                'valueUri' => $extension->value_uri,
                'valueUrl' => $extension->value_url,
                'valueUuid' => $extension->value_uuid,
                'valueAddress' => $this->createAddressResource($extension->valueAddress),
                'valueAge' => $this->createAgeResource($extension->valueAge),
                'valueAnnotation' => $this->createAnnotationResource($extension->valueAnnotation),
                'valueAttachment' => $this->createAttachmentResource($extension->valueAttachment),
                'valueCodeableConcept' => $this->createCodeableConceptResource($extension->valueCodeableConcept),
                'valueCoding' => $this->createCodingResource($extension->valueCoding),
                'valueContactPoint' => $this->createContactPointResource($extension->valueContactPoint),
                // 'valueCount' => $this->createCountResource($extension->valueCount),
                // 'valueDistance' => $this->createDistanceResource($extension->valueDistance),
                'valueDuration' => $this->createDurationResource($extension->valueDuration),
                'valueHumanName' => $this->createHumanNameResource($extension->valueHumanName),
                'valueIdentifier' => $this->createIdentifierResource($extension->valueIdentifier),
                // 'valueMoney' => $this->createMoneyResource($extension->valueMoney),
                'valuePeriod' => $this->createPeriodResource($extension->valuePeriod),
                'valueQuantity' => $this->createQuantityResource($extension->valueQuantity),
                'valueRange' => $this->createRangeResource($extension->valueRange),
                'valueRatio' => $this->createRatioResource($extension->valueRatio),
                'valueSampledData' => $this->createSampledDataResource($extension->valueSampledData),
                // 'valueSignature' => $this->createSignatureResource($extension->valueSignature),
                'valueTiming' => $this->createTimingResource($extension->valueTiming),
                // 'valueContactDetail' => $this->createContactDetailResource($extension->valueContactDetail),
                // 'valueContributor' => $this->createContributorResource($extension->valueContributor),
                // 'valueDataRequirement' => $this->createDataRequirementResource($extension->valueDataRequirement),
                // 'valueExpression' => $this->createExpressionResource($extension->valueExpression),
                // 'valueParameterDefinition' => $this->createParameterDefinitionResource($extension->valueParameterDefinition),
                // 'valueRelatedArtifact' => $this->createRelatedArtifactResource($extension->valueRelatedArtifact),
                // 'valueTriggerDefinition' => $this->createTriggerDefinitionResource($extension->valueTriggerDefinition),
                // 'valueUsageContext' => $this->createUsageContextResource($extension->valueUsageContext),
                'valueDosage' => $this->createDosageResource($extension->valueDosage),
                // 'valueMeta' => $this->createMetaResource($extension->valueMeta),
                'valueReference' => $this->createReferenceResource($extension->valueReference),
            ];
        } else {
            return null;
        }
    }

    public function createPeriodResource($period)
    {
        if (!empty($period)) {
            $periodResource = [
                'start' => $this->parseDateTime($period->start),
                'end' => $this->parseDateTime($period->end),
            ];

            return $periodResource;
        } else {
            return null;
        }
    }

    public function searchSnomed(string $ecl, string $term, Client $client)
    {
        $headers = [
            'Accept' => 'application/json',
            'Accept-Language' => 'en-X-900000000000509007,en-X-900000000000508004,en',
        ];

        $query = [
            'term' => $term,
            'ecl' => $ecl,
            'includeLeafFlag' => 'false',
            'form' => 'inferred',
            'offset' => 0,
            'limit' => 50,
        ];

        $response = $client->request('GET', Codesystems::SNOMEDCT['url'], [
            'headers' => $headers,
            'query' => $query,
        ]);

        $body = json_decode($response->getBody()->getContents(), true);

        return $body;
    }

    public function querySnomedCode($code): string
    {
        $client = new Client();

        $response = $client->request('GET', 'https://browser.ihtsdotools.org/snowstorm/snomed-ct/MAIN/concepts/' . $code, [
            'headers' => [
                'accept' => 'application/json',
                'Accept-Language' => 'en-X-900000000000509007,en-X-900000000000508004,en',
            ],
        ]);

        $body = $response->getBody();
        $data = json_decode($body, true);

        return $data['fsn']['term'];
    }


    /**
     * Get the data of a specific resource type.
     *
     * @param string $resourceType The type of resource to retrieve data from.
     *
     * @return mixed The data of the specified resource type.
     *
     * @throws ModelNotFoundException If the data is not found.
     */
    public function getData($resourceType)
    {
        $data = $this->resource ? $this->resource->$resourceType->first() : null;

        if ($data == null) {
            throw new ModelNotFoundException('Data tidak ditemukan');
        } else {
            return $data;
        }
    }


    public function parseDateTime($date)
    {
        if ($date != null) {
            // // Create a DateTime object with the input date
            // $dateTime = new DateTime($date);

            // // Set the desired time zone for Jakarta (+07:00)
            // $dateTime->setTimezone(new DateTimeZone('Asia/Jakarta'));

            // // Format the date in the desired format
            // $formattedDate = $dateTime->format('Y-m-d\TH:i:sP');

            return Carbon::parse($date)->tz('Asia/Jakarta')->format('Y-m-d\TH:i:sP');
        } else {
            return null;
        }
    }

    public function parseDate($date)
    {
        if ($date != null) {
            return Carbon::parse($date)->format('Y-m-d');
        } else {
            return null;
        }
    }

    public function parseTime($date)
    {
        if ($date != null) {
            return Carbon::parse($date)->tz('Asia/Jakarta')->format('H:i:s');
        } else {
            return null;
        }
    }

    public function mergeArray(...$arrays)
    {
        $arr = [];

        foreach ($arrays as $a) {
            if ($a != null) {
                $arr = array_merge($arr, $a);
            }
        }

        return $arr;
    }

    public function removeEmptyValues($data)
    {
        // Recursively iterate through the array
        foreach ($data as $key => &$value) {
            // Check if the value is an array
            if (is_array($value)) {
                // Call the function recursively for nested arrays
                $value = $this->removeEmptyValues($value);

                // Remove the element if it becomes an empty array after processing
                if (empty($value)) {
                    unset($data[$key]);
                }
            } else {
                // Remove null or empty string values
                if ($value === null || $value === '') {
                    unset($data[$key]);
                }
            }
        }

        return $data;
    }
}
