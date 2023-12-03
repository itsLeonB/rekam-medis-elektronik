<?php

namespace Database\Seeders;

use App\Constants;
use App\Models\Condition;
use App\Models\Resource;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class IdFhirResourceSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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

            switch ($resType) {
                case 'Organization':
                    $this->seedOrganization($res, $resText);
                    break;
                case 'Location':
                    $this->seedLocation($res, $resText);
                    break;
                case 'Practitioner':
                    $this->seedPractitioner($res, $resText);
                    break;
                case 'Patient':
                    $this->seedPatient($res, $resText);
                    break;
                case 'Encounter':
                    $this->seedEncounter($res, $resText);
                    break;
                case 'Condition':
                    $this->seedCondition($res, $resText);
                    break;
                case 'QuestionnaireResponse':
                    $this->seedQuestionnaireResponse($res, $resText);
                    break;
                default:
                    break;
            }
        }
    }


    private function seedCondition($resource, $resourceText)
    {
        $resourceContent = json_decode($resourceText, true);

        $conditionData = [
            'clinical_status' => returnAttribute($resourceContent, ['clinicalStatus', 'coding', 0, 'code']),
            'verification_status' => returnAttribute($resourceContent, ['verificationStatus', 'coding', 0, 'code']),
            'category' => $this->returnMultiCodeableConcept(returnAttribute($resourceContent, ['category'])),
            'severity' => returnAttribute($resourceContent, ['severity', 'coding', 0, 'code']),
            'code_system' => returnAttribute($resourceContent, ['code', 'coding', 0, 'system']),
            'code_code' => returnAttribute($resourceContent, ['code', 'coding', 0, 'code']),
            'code_display' => returnAttribute($resourceContent, ['code', 'coding', 0, 'display']),
            'body_site' => $this->returnMultiCodeableConcept(returnAttribute($resourceContent, ['bodySite'])),
            'subject' => returnAttribute($resourceContent, ['subject', 'reference']),
            'encounter' => returnAttribute($resourceContent, ['encounter', 'reference']),
            'onset' => returnVariableAttribute($resourceContent, Condition::ONSET),
            'abatement' => returnVariableAttribute($resourceContent, Condition::ABATEMENT),
            'recorded_date' => returnAttribute($resourceContent, ['recordedDate']),
            'recorder' => returnAttribute($resourceContent, ['recorder', 'reference']),
            'asserter' => returnAttribute($resourceContent, ['asserter', 'reference'])
        ];

        $condition = $resource->condition()->createQuietly($conditionData);
        $condition->identifier()->createManyQuietly($this->returnIdentifier(returnAttribute($resourceContent, ['identifier'])));

        $stageData = returnAttribute($resourceContent, ['stage']);
        if (!empty($stageData)) {
            foreach ($stageData as $s) {
                $stage = [
                    'summary' => returnAttribute($s, ['summary', 'coding', 0, 'code']),
                    'assessment' => $this->returnMultiReference(returnAttribute($s, ['assessment'])),
                    'type' => returnAttribute($s, ['type', 'coding', 0, 'code'])
                ];
                $condition->stage()->createQuietly($stage);
            }
        }

        $evidenceData = returnAttribute($resourceContent, ['evidence']);
        if (!empty($evidenceData)) {
            foreach ($evidenceData as $e) {
                $evidence = [
                    'code' => $this->returnMultiCodeableConcept(returnAttribute($e, ['code'])),
                    'detail' => $this->returnMultiReference(returnAttribute($e, ['detail']))
                ];
                $condition->evidence()->createQuietly($evidence);
            }
        }

        $condition->note()->createManyQuietly($this->returnAnnotation(returnAttribute($resourceContent, ['note'])));
    }


    private function seedEncounter($resource, $resourceText)
    {
        $resourceContent = json_decode($resourceText, true);

        $encounterData = [
            'status' => returnAttribute($resourceContent, ['status']),
            'class' => returnAttribute($resourceContent, ['class', 'code']),
            'type' => $this->returnMultiCodeableConcept(returnAttribute($resourceContent, ['type'])),
            'service_type' => returnAttribute($resourceContent, ['serviceType', 'coding', 0, 'code']),
            'priority' => returnAttribute($resourceContent, ['priority', 'coding', 0, 'code']),
            'subject' => returnAttribute($resourceContent, ['subject', 'reference']),
            'episode_of_care' => $this->returnMultiReference(returnAttribute($resourceContent, ['episodeOfCare'])),
            'based_on' => $this->returnMultiReference(returnAttribute($resourceContent, ['basedOn'])),
            'period_start' => returnAttribute($resourceContent, ['period', 'start']),
            'period_end' => returnAttribute($resourceContent, ['period', 'end']),
            'length_value' => returnAttribute($resourceContent, ['length', 'value']),
            'length_comparator' => returnAttribute($resourceContent, ['length', 'comparator']),
            'length_unit' => returnAttribute($resourceContent, ['length', 'unit']),
            'length_system' => returnAttribute($resourceContent, ['length', 'system']),
            'length_code' => returnAttribute($resourceContent, ['length', 'code']),
            'reason_code' => $this->returnMultiCodeableConcept(returnAttribute($resourceContent, ['reasonCode'])),
            'reason_reference' => $this->returnMultiReference(returnAttribute($resourceContent, ['reasonReference'])),
            'account' => $this->returnMultiReference(returnAttribute($resourceContent, ['account'])),
            'hospitalization_preadmission_identifier_system' => returnAttribute($resourceContent, ['hospitalization', 'preAdmissionIdentifier', 'system']),
            'hospitalization_preadmission_identifier_use' => returnAttribute($resourceContent, ['hospitalization', 'preAdmissionIdentifier', 'use']),
            'hospitalization_preadmission_identifier_value' => returnAttribute($resourceContent, ['hospitalization', 'preAdmissionIdentifier', 'value']),
            'hospitalization_origin' => returnAttribute($resourceContent, ['hospitalization', 'origin', 'reference']),
            'hospitalization_admit_source' => returnAttribute($resourceContent, ['hospitalization', 'admitSource', 'coding', 0, 'code']),
            'hospitalization_re_admission' => returnAttribute($resourceContent, ['hospitalization', 'reAdmission', 'coding', 0, 'code']),
            'hospitalization_diet_preference' => $this->returnMultiCodeableConcept(returnAttribute($resourceContent, ['hospitalization', 'dietPreference'])),
            'hospitalization_special_arrangement' => $this->returnMultiCodeableConcept(returnAttribute($resourceContent, ['hospitalization', 'specialArrangement'])),
            'hospitalization_destination' => returnAttribute($resourceContent, ['hospitalization', 'destination', 'reference']),
            'hospitalization_discharge_disposition' => returnAttribute($resourceContent, ['hospitalization', 'dischargeDisposition', 'coding', 0, 'code']),
            'service_provider' => returnAttribute($resourceContent, ['serviceProvider', 'reference']),
            'part_of' => returnAttribute($resourceContent, ['partOf', 'reference'])
        ];

        $encounter = $resource->encounter()->createQuietly($encounterData);
        $encounter->identifier()->createManyQuietly($this->returnIdentifier(returnAttribute($resourceContent, ['identifier'])));

        $statusHistories = returnAttribute($resourceContent, ['statusHistory']);
        if (!empty($statusHistories)) {
            foreach ($statusHistories as $sh) {
                $statusHistoryData = [
                    'status' => returnAttribute($sh, ['status']),
                    'period_start' => returnAttribute($sh, ['period', 'start']),
                    'period_end' => returnAttribute($sh, ['period', 'end'])
                ];
                $encounter->statusHistory()->createQuietly($statusHistoryData);
            }
        }

        $classHistories = returnAttribute($resourceContent, ['classHistory']);
        if (!empty($classHistories)) {
            foreach ($classHistories as $ch) {
                $classHistory = [
                    'status' => returnAttribute($ch, ['status']),
                    'period_start' => returnAttribute($ch, ['period', 'start']),
                    'period_end' => returnAttribute($ch, ['period', 'end'])
                ];
                $encounter->classHistory()->createQuietly($classHistory);
            }
        }

        $participants = returnAttribute($resourceContent, ['participant']);
        if (!empty($participants)) {
            foreach ($participants as $p) {
                $participant = [
                    'type' => $this->returnMultiCodeableConcept(returnAttribute($p, ['type'])),
                    'individual' => returnAttribute($p, ['individual', 'reference'])
                ];
                $encounter->participant()->createQuietly($participant);
            }
        }

        $diagnoses = returnAttribute($resourceContent, ['diagnosis']);
        if (!empty($diagnoses)) {
            foreach ($diagnoses as $d) {
                $diagnosis = [
                    'condition' => returnAttribute($d, ['condition', 'reference']),
                    'use' => returnAttribute($d, ['use', 'coding', 0, 'code']),
                    'rank' => returnAttribute($d, ['rank']),
                ];
                $encounter->diagnosis()->createQuietly($diagnosis);
            }
        }

        $locations = returnAttribute($resourceContent, ['location']);
        if (!empty($locations)) {
            foreach ($locations as $l) {
                $location = [
                    'location' => returnAttribute($l, ['location', 'reference']),
                ];
                $encounter->location()->createQuietly($location);
            }
        }
    }


    private function seedQuestionnaireResponse($resource, $resourceText)
    {
        $resourceContent = json_decode($resourceText, true);
        $items = returnAttribute($resourceContent, ['item']);

        $questionnaireData = [
            'identifier_system' => returnAttribute($resourceContent, ['identifier', 'system']),
            'identifier_use' => returnAttribute($resourceContent, ['identifier', 'use']),
            'identifier_value' => returnAttribute($resourceContent, ['identifier', 'value']),
            'based_on' => $this->returnMultiReference(returnAttribute($resourceContent, ['basedOn'])),
            'part_of' => $this->returnMultiReference(returnAttribute($resourceContent, ['partOf'])),
            'questionnaire' => returnAttribute($resourceContent, ['questionnaire']),
            'status' => returnAttribute($resourceContent, ['status']),
            'subject' => returnAttribute($resourceContent, ['subject', 'reference']),
            'encounter' => returnAttribute($resourceContent, ['encounter', 'reference']),
            'authored' => returnAttribute($resourceContent, ['authored']),
            'author' => returnAttribute($resourceContent, ['author', 'reference']),
            'source' => returnAttribute($resourceContent, ['source', 'reference']),
        ];

        $questionnaireResponse = $resource->questionnaireResponse()->createQuietly($questionnaireData);

        if (!empty($items)) {
            foreach ($items as $i) {
                $itemData = [
                    'link_id' => returnAttribute($i, ['linkId']),
                    'definition' => returnAttribute($i, ['definition']),
                    'text' => returnAttribute($i, ['text']),
                    'answer' => returnAttribute($i, ['answer']),
                    'item' => returnAttribute($i, ['item'])
                ];

                $questionnaireResponse->item()->createQuietly($itemData);
            }
        }
    }


    private function seedPatient($resource, $resourceText)
    {
        $resourceContent = json_decode($resourceText, true);
        $name = returnHumanName(returnAttribute($resourceContent, ['name', 0]));
        $extension = returnAttribute($resourceContent, ['extension']);
        $birthPlace = $this->returnBirthPlace($extension);
        $contacts = returnAttribute($resourceContent, ['contact']);

        $patientData = [
            'active' => returnAttribute($resourceContent, ['active']),
            'name' => $name['name'],
            'prefix' => $name['prefix'],
            'suffix' => $name['suffix'],
            'gender' => returnAttribute($resourceContent, ['gender'], 'unknown'),
            'birth_date' => returnAttribute($resourceContent, ['birthDate']),
            'deceased' => returnVariableAttribute($resourceContent, ['deceasedBoolean', 'deceasedDateTime']),
            'marital_status' => returnAttribute($resourceContent, ['maritalStatus', 'coding', 0, 'code']),
            'multiple_birth' => returnVariableAttribute($resourceContent, ['multipleBirthBoolean', 'multipleBirthInteger']),
            'communication' => $this->returnCommunication(returnAttribute($resourceContent, ['communication'])),
            'general_practitioner' => $this->returnMultiReference(returnAttribute($resourceContent, ['generalPractitioner'])),
            'managing_organization' => returnAttribute($resourceContent, ['managingOrganization', 'reference']),
            'link' => $this->returnLink(returnAttribute($resourceContent, ['link'])),
            'birth_city' => $birthPlace['city'],
            'birth_country' => $birthPlace['country']
        ];

        // try {
        $patient = $resource->patient()->createQuietly($patientData);
        // } catch (Exception $e) {
        //     dd($patientData);
        // }
        $patient->identifier()->createManyQuietly($this->returnIdentifier(returnAttribute($resourceContent, ['identifier'])));
        $patient->telecom()->createManyQuietly($this->returnTelecom(returnAttribute($resourceContent, ['telecom'])));
        $patient->address()->createManyQuietly($this->returnAddress(returnAttribute($resourceContent, ['address'])));

        if (!empty($contacts)) {
            foreach ($contacts as $c) {
                $contactName = returnHumanName(returnAttribute($c, ['name']));
                $addressExtension = $this->returnAdministrativeAddress(returnAttribute($c, ['address', 'extension']));

                $contactData = merge_array(
                    [
                        'relationship' => $this->returnMultiCodeableConcept(returnAttribute($c, ['relationship'])),
                        'name' => $contactName['name'],
                        'prefix' => $contactName['prefix'],
                        'suffix' => $contactName['suffix'],
                        'gender' => returnAttribute($c, ['gender'], 'unknown'),
                        'address_use' => returnAttribute($c, ['address', 'use']),
                        'address_line' => returnAttribute($c, ['address', 'line']),
                        'country' => returnAttribute($c, ['address', 'country']),
                        'postal_code' => returnAttribute($c, ['address', 'postalCode']),
                    ],
                    $addressExtension
                );

                $contact = $patient->contact()->createQuietly($contactData);
                $contact->telecom()->createManyQuietly($this->returnTelecom(returnAttribute($c, ['telecom'])));
            }
        }
    }


    private function returnBirthPlace($extensions): array
    {
        $birthPlace = [
            'city' => null,
            'country' => null
        ];

        if (!empty($extensions)) {
            foreach ($extensions as $e) {
                if ($e['url'] == "https://fhir.kemkes.go.id/r4/StructureDefinition/birthPlace") {
                    $birthPlace = [
                        'city' => returnAttribute($e, ['valueAddress', 'city']),
                        'country' => returnAttribute($e, ['valueAddress', 'country'])
                    ];
                }
            }
        }

        return $birthPlace;
    }


    private function returnLink($links): array
    {
        $link = [];

        if (!empty($links)) {
            foreach ($links as $l) {
                $link[] = [
                    'other' => returnAttribute($l, ['other', 'reference']),
                    'type' => returnAttribute($l, ['type'])
                ];
            }
        }

        return $link;
    }


    private function returnCommunication($communications): array
    {
        $comms = [];

        if (!empty($communications)) {
            foreach ($communications as $c) {
                $comms[] = returnAttribute($c, ['coding', 0, 'code']);
            }
        }

        return $comms;
    }


    private function seedPractitioner($resource, $resourceText)
    {
        $resourceContent = json_decode($resourceText, true);
        $identifiers = returnAttribute($resourceContent, ['identifier']);
        $name = returnHumanName(returnAttribute($resourceContent, ['name']));

        $practitionerData = [
            'nik' => $this->returnNik($identifiers),
            'nakes_id' => $this->returnNakesId($identifiers),
            'active' => returnAttribute($resourceContent, ['active']),
            'name' => $name['name'],
            'prefix' => $name['prefix'],
            'suffix' => $name['suffix'],
            'gender' => returnAttribute($resourceContent, ['gender'], 'unknown'),
            'birth_date' => returnAttribute($resourceContent, ['birthDate']),
            'communication' => returnAttribute($resourceContent, ['communication'])
        ];

        $practitioner = $resource->practitioner()->createQuietly($practitionerData);
        $practitioner->telecom()->createManyQuietly($this->returnTelecom(returnAttribute($resourceContent, ['telecom'])));
        $practitioner->address()->createManyQuietly($this->returnAddress(returnAttribute($resourceContent, ['address'])));
        $practitioner->qualification()->createManyQuietly($this->returnQualification(returnAttribute($resourceContent, ['qualification'])));
    }


    private function returnQualification($qualifications): array
    {
        $qualification = [];

        if (!empty($qualifications)) {
            foreach ($qualifications as $q) {
                $qualification[] = [
                    'identifier' => returnAttribute($q, ['identifier']),
                    'code' => returnAttribute($q, ['code']),
                    'period_start' => returnAttribute($q, ['period', 'start']),
                    'period_end' => returnAttribute($q, ['period', 'end']),
                    'issuer' => returnAttribute($q, ['issuer', 'reference'])
                ];
            }
        }

        return $qualification;
    }


    private function returnNik($identifiers)
    {
        if (!empty($identifiers)) {
            foreach ($identifiers as $i) {
                if ($i['system'] == Constants::NIK_SYSTEM) {
                    return $i['value'];
                }
            }
        } else {
            return null;
        }
    }


    private function returnNakesId($identifiers)
    {
        if (!empty($identifiers)) {
            foreach ($identifiers as $i) {
                if ($i['system'] == Constants::NAKES_SYSTEM) {
                    return $i['value'];
                }
            }
        } else {
            return null;
        }
    }


    private function returnAdministrativeAddress($addressExtension): array
    {
        $addressDetails = [];

        if (!empty($addressExtension)) {
            foreach ($addressExtension as $extension) {
                $url = $extension['url'];
                $value = (int)preg_replace("/[^0-9]/", "", $extension['valueCode']);
                $addressDetails[$url] = $value;
            }
        }

        return $addressDetails;
    }


    private function seedLocation($resource, $resourceText)
    {
        $resourceContent = json_decode($resourceText, true);

        $addressDetails = [];
        $extensionData = returnAttribute($resourceContent, ['address', 'extension', 0, 'extension'], null);

        if (!empty($extensionData)) {
            foreach ($extensionData as $extension) {
                $url = $extension['url'];
                $value = (int)preg_replace("/[^0-9]/", "", $extension['valueCode']);
                $addressDetails[$url] = $value;
            }
        }

        $locationData = array_merge(
            [
                'status' => returnAttribute($resourceContent, ['status']),
                'operational_status' => returnAttribute($resourceContent, ['operationalStatus', 'code']),
                'name' => returnAttribute($resourceContent, ['name'], 'unknown'),
                'alias' => returnAttribute($resourceContent, ['alias']),
                'description' => returnAttribute($resourceContent, ['description']),
                'mode' => returnAttribute($resourceContent, ['mode']),
                'type' => $this->returnLocationType(returnAttribute($resourceContent, ['type'])),
                'address_use' => returnAttribute($resourceContent, ['address', 'use']),
                'address_line' => returnAttribute($resourceContent, ['address', 'line']),
                'country' => returnAttribute($resourceContent, ['address', 'country']),
                'postal_code' => returnAttribute($resourceContent, ['address', 'postalCode']),
                'physical_type' => returnAttribute($resourceContent, ['physicalType', 'coding', 0, 'code']),
                'longitude' => returnAttribute($resourceContent, ['position', 'longitude']),
                'latitude' => returnAttribute($resourceContent, ['position', 'latitude']),
                'altitude' => returnAttribute($resourceContent, ['position', 'altitude']),
                'managing_organization' => returnAttribute($resourceContent, ['managingOrganization', 'reference']),
                'part_of' => returnAttribute($resourceContent, ['partOf', 'reference']),
                'availability_exceptions' => returnAttribute($resourceContent, ['availabilityExceptions']),
                'endpoint' => $this->returnMultiReference(returnAttribute($resourceContent, ['endpoint'])),
                'service_class' => $this->returnLocationServiceClass(returnAttribute($resourceContent, ['extension']))
            ],
            $addressDetails
        );

        $location = $resource->location()->createQuietly($locationData);
        $location->identifier()->createManyQuietly($this->returnIdentifier(returnAttribute($resourceContent, ['identifier'])));
        $location->telecom()->createManyQuietly($this->returnTelecom(returnAttribute($resourceContent, ['telecom'])));
        $location->operationHours()->createManyQuietly($this->returnOperationHours(returnAttribute($resourceContent, ['hoursOfOperation'])));
    }


    private function returnOperationHours($operationHours): array
    {
        $hour = [];

        if (!empty($operationHours)) {
            foreach ($operationHours as $o) {
                $hour[] = [
                    'days_of_week' => returnAttribute($o, ['daysOfWeek']),
                    'all_day' => returnAttribute($o, ['allDay']),
                    'opening_time' => returnAttribute($o, ['openingTime']),
                    'closing_time' => returnAttribute($o, ['closingTime'])
                ];
            }
        }

        return $hour;
    }


    private function returnLocationServiceClass($extension)
    {
        if (!empty($extension)) {
            foreach ($extension as $e) {
                if ($e['url'] == "https://fhir.kemkes.go.id/r4/StructureDefinition/LocationServiceClass") {
                    return returnAttribute($e, ['valueCodeableConcept', 'coding', 0, 'code']);
                } else {
                    return null;
                }
            }
        } else {
            return null;
        }
    }


    private function returnLocationType($types): array
    {
        $type = [];

        if (!empty($types)) {
            foreach ($types as $t) {
                $type[] = returnAttribute($t, ['coding', 0, 'code']);
            }
        }

        return $type;
    }


    private function seedOrganization($resource, $resourceText)
    {
        $resourceContent = json_decode($resourceText, true);
        $contactData = returnAttribute($resourceContent, ['contact']);

        $organizationData = [
            'active' => returnAttribute($resourceContent, ['active'], false),
            'type' => $this->returnMultiCodeableConcept(returnAttribute($resourceContent, ['type'])),
            'name' => returnAttribute($resourceContent, ['name'], 'unknown'),
            'alias' => returnAttribute($resourceContent, ['alias']),
            'part_of' => returnAttribute($resourceContent, ['partOf', 'reference']),
            'endpoint' => $this->returnMultiReference(returnAttribute($resourceContent, ['endpoint'])),
        ];

        $organizationData = removeEmptyValues($organizationData);
        $organization = $resource->organization()->createQuietly($organizationData);
        $organization->identifier()->createManyQuietly($this->returnIdentifier(returnAttribute($resourceContent, ['identifier'])));
        $organization->telecom()->createManyQuietly($this->returnTelecom(returnAttribute($resourceContent, ['telecom'])));
        $organization->address()->createManyQuietly($this->returnAddress(returnAttribute($resourceContent, ['address'])));

        if (!empty($contactData)) {
            foreach ($contactData as $c) {
                $addressDetails = [];
                $extensionData = returnAttribute($c, ['address', 'extension', 0, 'extension'], null);

                if (!empty($extensionData)) {
                    foreach ($extensionData as $extension) {
                        $url = $extension['url'];
                        $value = (int)preg_replace("/[^0-9]/", "", $extension['valueCode']);
                        $addressDetails[$url] = $value;
                    }
                }

                $line = returnAttribute($c, ['address', 'line']) ? returnAttribute($c, ['address', 'line']) : returnAttribute($c, ['address', 'text'], '');

                $contactArray = array_merge(
                    $addressDetails,
                    [
                        'purpose' => returnAttribute($c, ['purpose', 'coding', 0, 'code']),
                        'name_use' => returnAttribute($c, ['name', 'use']),
                        'name_text' => returnAttribute($c, ['name', 'text']),
                        'address_use' => returnAttribute($c, ['address', 'use']),
                        'address_type' => returnAttribute($c, ['address', 'type']),
                        'address_line' => $line,
                        'country' => returnAttribute($c, ['address', 'country'], 'ID'),
                        'postal_code' => returnAttribute($c, ['address', 'postalCode']),
                    ],
                );
                $contact = $organization->contact()->createQuietly($contactArray);
                $contact->telecom()->createManyQuietly($this->returnTelecom(returnAttribute($c, ['telecom'])));
            }
        }
    }


    private function returnAddress($addresses): array
    {
        $address = [];
        $addressDetails = [];

        if (!empty($addresses)) {
            foreach ($addresses as $a) {
                $extensionData = returnAttribute($a, ['extension', 0, 'extension'], null);

                if (!empty($extensionData)) {
                    foreach ($extensionData as $extension) {
                        $url = $extension['url'];
                        $value = (int)preg_replace("/[^0-9]/", "", $extension['valueCode']);
                        $addressDetails[$url] = $value;
                    }
                }

                $line = returnAttribute($a, ['line']) ? returnAttribute($a, ['line']) : returnAttribute($a, ['text']);

                $address[] = array_merge(
                    $addressDetails,
                    [
                        'use' => returnAttribute($a, ['use']),
                        'line' => $line,
                        'country' => returnAttribute($a, ['country'], 'ID'),
                        'postal_code' => returnAttribute($a, ['postalCode']),
                    ],
                );
            }
        }

        return $address;
    }


    private function returnTelecom($telecoms): array
    {
        $telecom = [];

        if (!empty($telecoms)) {
            foreach ($telecoms as $t) {
                $telecom[] = [
                    'system' => returnAttribute($t, ['system']),
                    'use' => returnAttribute($t, ['use']),
                    'value' => returnAttribute($t, ['value'])
                ];
            }
        }

        return $telecom;
    }


    private function returnMultiCodeableConcept($codeableConcepts): array
    {
        $codeableConcept = [];

        if (!empty($codeableConcepts)) {
            foreach ($codeableConcepts as $cc) {
                $codeableConcept[] = returnAttribute($cc, ['coding', 0, 'code']);
            }
        }

        return $codeableConcept;
    }


    private function returnMultiReference($references): array
    {
        $reference = [];

        if (!empty($references)) {
            foreach ($references as $r) {
                $reference[] = returnAttribute($r, ['reference']);
            }
        }

        return $reference;
    }


    private function returnIdentifier($identifiers): array
    {
        $identifier = [];

        if (!empty($identifiers)) {
            foreach ($identifiers as $i) {
                $identifier[] = [
                    'system' => returnAttribute($i, ['system'], 'unknown'),
                    'use' => returnAttribute($i, ['use']),
                    'value' => returnAttribute($i, ['value'])
                ];
            }
        }
        return $identifier;
    }


    private function returnReference($references): array
    {
        $reference = [];

        if (!empty($references)) {
            foreach ($references as $r) {
                $reference[] = [
                    'reference' => returnAttribute($r, ['reference'])
                ];
            }
        }

        return $reference;
    }


    private function returnProcessing($processings): array
    {
        $processing = [];

        if (!empty($processings)) {
            foreach ($processings as $p) {
                $processing[] = [
                    'description' => returnAttribute($p, ['description']),
                    'procedure' => returnAttribute($p, ['procedure', 'coding', 0, 'code']),
                    'additive' => returnAttribute($p, ['additive']),
                    'time' => returnVariableAttribute($p, ['timeDateTime', 'timePeriod'])
                ];
            }
        }

        return $processing;
    }


    private function returnCodeableConcept($codeableConcepts): array
    {
        $codeableConcept = [];

        if (!empty($codeableConcepts)) {
            foreach ($codeableConcepts as $cc) {
                $codeableConcept[] = [
                    'system' => returnAttribute($cc, ['coding', 0, 'system']),
                    'code' => returnAttribute($cc, ['coding', 0, 'code']),
                    'display' => returnAttribute($cc, ['coding', 0, 'display'])
                ];
            }
        }

        return $codeableConcept;
    }


    private function returnAnnotation($annotations): array
    {
        $annotation = [];

        if (!empty($annotations)) {
            foreach ($annotations as $a) {
                $annotation[] = [
                    'author' => returnVariableAttribute($a, 'author', ['String', 'Reference']),
                    'time' => returnAttribute($a, ['time']),
                    'text' => returnAttribute($a, ['text'])
                ];
            }
        }

        return $annotation;
    }
}
