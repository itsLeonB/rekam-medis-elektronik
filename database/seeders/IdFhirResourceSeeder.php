<?php

namespace Database\Seeders;

use App\Constants;
use App\Models\Resource;
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
                default:
                    break;
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
            'deceased' => returnVariableAttribute($resourceContent, 'deceased', ['Boolean', 'DateTime']),
            'marital_status' => returnAttribute($resourceContent, ['maritalStatus', 'coding', 0, 'code']),
            'multiple_birth' => returnVariableAttribute($resourceContent, 'multipleBirth', ['Boolean', 'Integer']),
            'communication' => $this->returnCommunication(returnAttribute($resourceContent, ['communication'])),
            'general_practitioner' => $this->returnMultiReference(returnAttribute($resourceContent, ['generalPractitioner'])),
            'managing_organization' => returnAttribute($resourceContent, ['managingOrganization', 'reference']),
            'link' => $this->returnLink(returnAttribute($resourceContent, ['link'])),
            'birth_city' => $birthPlace['city'],
            'birth_country' => $birthPlace['country']
        ];

        $patient = $resource->patient()->createQuietly($patientData);
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
                } else {
                    return null;
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
                } else {
                    return null;
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
                    'time' => returnVariableAttribute($p, 'time', ['DateTime', 'Period'])
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
