<?php

use App\Models\CodeSystemAdmitSource;
use App\Models\CodeSystemClinicalStatus;
use App\Models\CodeSystemDischargeDisposition;
use App\Models\CodeSystemEncounterReason;
use App\Models\CodeSystemICD10;
use App\Models\CodeSystemParticipantType;
use App\Models\CodeSystemServiceType;
use App\Models\CodeSystemVerificationStatus;
use Illuminate\Support\Facades\Log;

function getName($resource)
{
    if (isset($resource['name']) && !empty($resource['name'])) {
        return $resource['name'];
    }

    return null;
}

function parseName($nameData)
{
    if ($nameData === null) {
        return null;
    }

    foreach ($nameData as $name) {

        $displayName = '';

        if (isset($name['text']) && !empty($name['text'])) {
            $displayName = $name['text'];
        } else {
            $nameParts = [];

            if (isset($name['prefix']) && !empty($name['prefix'])) {
                $nameParts[] = implode(' ', $name['prefix']);
            }

            $givenName = isset($name['given']) ? implode(' ', $name['given']) : '';
            $familyName = isset($name['family']) ? $name['family'] : '';

            if (!empty($givenName)) {
                $nameParts[] = $givenName;
            }

            if (!empty($familyName)) {
                $nameParts[] = $familyName;
            }

            if (isset($name['suffix']) && !empty($name['suffix'])) {
                $nameParts[] = implode(' ', $name['suffix']);
            }

            $displayName = implode(' ', $nameParts);
        }

        // if (isset($name['period']) && isset($name['period']['end'])) {
        //     $endDate = new DateTime($name['period']['end']);
        //     $currentDate = new DateTime();
        //     if ($endDate > $currentDate) {
        //         $displayName .= ' (Active)';
        //     }
        // }

        return $displayName;
    }

    return null;
}

function getFullName($nameData)
{
    if ($nameData === null) {
        return '';
    }

    foreach ($nameData as $name) {

        $displayName = '';

        if (isset($name['text']) && !empty($name['text'])) {
            $displayName = $name['text'];
        } else {
            $nameParts = [];

            $givenName = isset($name['given']) ? implode(' ', $name['given']) : '';
            $familyName = isset($name['family']) ? $name['family'] : '';

            if (!empty($givenName)) {
                $nameParts[] = $givenName;
            }

            if (!empty($familyName)) {
                $nameParts[] = $familyName;
            }

            $displayName = implode(' ', $nameParts);
        }
    }
    return $displayName;
}

function getPrefix($nameData)
{
    if ($nameData === null) {
        return null;
    }

    foreach ($nameData as $name) {

        $displayName = null;
        $nameParts = [];
        if (isset($name['prefix']) && !empty($name['prefix'])) {
            $nameParts[] = implode(' ', $name['prefix']);
        }
        $displayName = implode(' ', $nameParts);
    }
    return $displayName;
}

function getSuffix($nameData)
{
    if ($nameData === null) {
        return null;
    }

    foreach ($nameData as $name) {

        $displayName = null;
        $nameParts = [];
        if (isset($name['suffix']) && !empty($name['suffix'])) {
            $nameParts[] = implode(' ', $name['suffix']);
        }
        $displayName = implode(' ', $nameParts);
    }
    return $displayName;
}

function getIdentifier($resource)
{
    if (isset($resource['identifier']) && !empty($resource['identifier'])) {
        return $resource['identifier'];
    }

    return null;
}

function parseIdentifier($identifier)
{
    $identifierDetails = [];

    if (isset($identifier['system']) && !empty($identifier['system'])) {
        $identifierDetails['system'] = $identifier['system'];
    } else {
        $identifierDetails['system'] = '';
    }

    if (isset($identifier['use']) && !empty($identifier['use'])) {
        $identifierDetails['use'] = $identifier['use'];
    } else {
        $identifierDetails['use'] = '';
    }

    if (isset($identifier['value']) && !empty($identifier['value'])) {
        $identifierDetails['value'] = $identifier['value'];
    } else {
        $identifierDetails['value'] = '';
    }
    return $identifierDetails;
}

function getMRN($identifier)
{
    if ($identifier === null) {
        return null;
    }

    foreach ($identifier as $id) {
        if (isset($id['type']['coding']) && is_array($id['type']['coding'])) {
            foreach ($id['type']['coding'] as $coding) {
                if (isset($coding['code']) && $coding['code'] === 'MR') {
                    $value = $id['value'];
                    return $value;
                }
            }
        }
    }

    return null;
}

function getNik($identifier)
{
    if ($identifier === null) {
        return null;
    }

    foreach ($identifier as $id) {
        if (isset($id['system']) && $id['system'] === 'https://fhir.kemkes.go.id/id/nik') {
            $nik = $id['value'];
            return $nik;
        }
    }

    return null;
}

function getIhs($identifier)
{
    if ($identifier === null) {
        return null;
    }

    foreach ($identifier as $id) {
        if (isset($id['system']) && $id['system'] === 'https://fhir.kemkes.go.id/id/nakes-his-number') {
            $nik = $id['value'];
            return $nik;
        }
    }

    return null;
}

function getGender($resource)
{
    if (isset($resource['gender']) && !empty($resource['gender'])) {
        return $resource['gender'];
    }

    return 'unknown';
}

function getBirthDate($resource)
{
    if (isset($resource['birthDate']) && !empty($resource['birthDate'])) {
        return $resource['birthDate'];
    }

    return null;
}

function getTelecom($resource)
{
    if (isset($resource['telecom']) && !empty($resource['telecom'])) {
        return $resource['telecom'];
    }

    return null;
}

function getTelecomDetails($telecom)
{
    $telecomDetails = [];
    if (isset($telecom['system']) && !empty($telecom['system'])) {
        $telecomDetails['system'] = $telecom['system'];
    } else {
        $telecomDetails['system'] = '';
    }
    if (isset($telecom['use']) && !empty($telecom['use'])) {
        $telecomDetails['use'] = $telecom['use'];
    } else {
        $telecomDetails['use'] = '';
    }
    if (isset($telecom['value']) && !empty($telecom['value'])) {
        $telecomDetails['value'] = $telecom['value'];
    } else {
        $telecomDetails['value'] = '';
    }
    return $telecomDetails;
}

function getAddress($resource)
{
    if (isset($resource['address']) && !empty($resource['address'])) {
        return $resource['address'];
    }

    return null;
}

function getAddressDetails($address)
{
    $addressDetails = [];

    $addressDetails['rt'] = 0;
    $addressDetails['rw'] = 0;
    $addressDetails['village'] = 0;
    $addressDetails['district'] = 0;
    $addressDetails['city'] = 0;
    $addressDetails['province'] = 0;

    if (isset($address['use']) && !empty($address['use'])) {
        $addressDetails['use'] = $address['use'];
    } else {
        $addressDetails['use'] = '';
    }

    if (isset($address['line']) && !empty($address['line'])) {
        $addressDetails['line'] = $address['line'][0];
    } elseif (isset($address['text']) && !empty($address['text'])) {
        $addressDetails['line'] = $address['text'];
    } else {
        $addressDetails['line'] = '';
    }

    if (isset($address['postalCode']) && !empty($address['postalCode'])) {
        $addressDetails['postalCode'] = $address['postalCode'];
    } else {
        $addressDetails['postalCode'] = '';
    }

    if (isset($address['country']) && !empty($address['country'])) {
        $addressDetails['country'] = $address['country'];
    } else {
        $addressDetails['country'] = '';
    }

    if (isset($address['extension']) && !empty($address['extension'])) {
        $extensionData = $address['extension'][0]['extension'];

        foreach ($extensionData as $extension) {
            $url = $extension['url'];
            $value = (int)preg_replace("/[^0-9]/", "", $extension['valueCode']);
            $addressDetails[$url] = $value;
        }
    }

    return $addressDetails;
}

function getQualifications($resource)
{
    if (isset($resource['qualification']) && !empty($resource['qualification'])) {
        return $resource['qualification'];
    }

    return null;
}

function getQualificationDetails($qualification)
{
    $qualificationDetails = [];

    if (isset($qualification['code']['coding'][0]['code']) && !empty($qualification['code']['coding'][0]['code'])) {
        $qualificationDetails['code'] = $qualification['code']['coding'][0]['code'];
    } else {
        $qualificationDetails['code'] = '';
    }

    if (isset($qualification['code']['coding']) && !empty($qualification['code']['coding'])) {
        if (isset($qualification['code']['coding'][0]['code']) && !empty($qualification['code']['coding'][0]['code'])) {
            $qualificationDetails['code'] = $qualification['code']['coding'][0]['code'];
        } else {
            $qualificationDetails['code'] = '';
        }

        if (isset($qualification['code']['coding'][0]['system']) && !empty($qualification['code']['coding'][0]['system'])) {
            $qualificationDetails['system'] = $qualification['code']['coding'][0]['system'];
        } else {
            $qualificationDetails['system'] = '';
        }

        if (isset($qualification['code']['coding'][0]['display']) && !empty($qualification['code']['coding'][0]['display'])) {
            $qualificationDetails['display'] = $qualification['code']['coding'][0]['display'];
        } else {
            $qualificationDetails['display'] = '';
        }
    } else {
        $qualificationDetails['code'] = '';
        $qualificationDetails['system'] = '';
        $qualificationDetails['display'] = '';
    }

    if (isset($qualification['identifier'][0]['value']) && !empty($qualification['identifier'][0]['value'])) {
        $qualificationDetails['identifier'] = $qualification['identifier'][0]['value'];
    } else {
        $qualificationDetails['identifier'] = '';
    }

    if (isset($qualification['issuer']['reference']) && !empty($qualification['issuer']['reference'])) {
        $qualificationDetails['issuer'] = $qualification['issuer']['reference'];
    } else {
        $qualificationDetails['issuer'] = '';
    }

    if (isset($qualification['period']) && !empty($qualification['period'])) {
        if (isset($qualification['period']['start']) && !empty($qualification['period']['start'])) {
            $qualificationDetails['periodStart'] = date('Y-m-d', strtotime($qualification['period']['start']));
        } else {
            $qualificationDetails['periodStart'] = '1900-01-01';
        }
        if (isset($qualification['period']['end']) && !empty($qualification['period']['end'])) {
            $qualificationDetails['periodEnd'] = date('Y-m-d', strtotime($qualification['period']['end']));
        } else {
            $qualificationDetails['periodEnd'] = null;
        }
    } else {
        $qualificationDetails['periodStart'] = '1900-01-01';
        $qualificationDetails['periodEnd'] = null;
    }

    return $qualificationDetails;
}

function getExtension($resource)
{
    if (isset($resource['extension']) && !empty($resource['extension'])) {
        return $resource['extension'];
    }

    return null;
}

function getBirthPlace($extension)
{
    if (is_array($extension) || is_object($extension)) {
        foreach ($extension as $e) {
            if (isset($e['url']) && $e['url'] === 'https://fhir.kemkes.go.id/r4/StructureDefinition/birthPlace') {
                if (isset($e['extension']) && !empty($e['extension'])) {
                    $extensions = $e['extension'][0]['extension'];
                    foreach ($extensions as $ex) {
                        if (isset($ex['url']) && $ex['url'] === 'city') {
                            return $ex['valueCode'];
                        } else {
                            return null;
                        }
                    }
                } elseif (isset($e['valueAddress']) && !empty($e['valueAddress'])) {
                    if (isset($e['valueAddress']['city']) && !empty($e['valueAddress']['city'])) {
                        $city = $e['valueAddress']['city'];
                        return $city;
                    } else {
                        return null;
                    }
                }
            } else {
                return null;
            }
        }
    } else {
        return null;
    }
}

function getDeceased($resource)
{
    if (isset($resource['deceasedBoolean']) && !empty($resource['deceasedBoolean'])) {
        if ($resource['deceasedBoolean'] == true) {
            return '1900-01-01';
        } else {
            return null;
        }
    } elseif (isset($resource['deceasedDateTime']) && !empty($resource['deceasedDateTime'])) {
        return $resource['deceasedDateTime'];
    } else {
        return null;
    }
}

function getMaritalStatus($resource)
{
    if (isset($resource['maritalStatus']) && !empty($resource['maritalStatus'])) {
        $coding = $resource['maritalStatus']['coding'];
        if (isset($coding) && !empty($coding)) {
            foreach ($coding as $c) {
                if ($c['system'] === 'http://terminology.hl7.org/CodeSystem/v3-MaritalStatus') {
                    return $c['code'];
                } else {
                    return 'A';
                }
            }
        } else {
            return 'A';
        }
    } else {
        return 'A';
    }
}

function getMultipleBirth($resource)
{
    if (isset($resource['multipleBirthBoolean']) && !empty($resource['multipleBirthBoolean'])) {
        return $resource['multipleBirthBoolean'];
    } elseif (isset($resource['multipleBirthInteger']) && !empty($resource['multipleBirthInteger'])) {
        if ($resource['multipleBirthInteger'] > 0) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function getLanguage($resource)
{
    if (isset($resource['communication'][0]['language']['coding'][0]['code']) && !empty($resource['communication'][0]['language']['coding'][0]['code'])) {
        return $resource['communication'][0]['language']['coding'][0]['code'];
    } else {
        return '';
    }
}

function getContact($resource)
{
    if (isset($resource['contact']) && !empty($resource['contact'])) {
        return $resource['contact'];
    }

    return null;
}

function getContactDetails($contact)
{
    $contactDetails = [];

    if (isset($contact['relationship']) && !empty($resource['relationship'])) {
        $relationCoding = $contact['relationship'][0]['coding'];
        if (isset($relationCoding) && !empty($relationCoding)) {
            foreach ($relationCoding as $rc) {
                if ($rc['system'] === 'http://terminology.hl7.org/CodeSystem/v2-0131') {
                    $contactDetails['relationship'] = $rc['code'];
                } else {
                    $contactDetails['relationship'] = 'U';
                }
            }
        } else {
            $contactDetails['relationship'] = 'U';
        }
    } else {
        $contactDetails['relationship'] = 'U';
    }

    $contactName = getName($contact);
    $contactDetails['name'] = getFullName($contactName);
    $contactDetails['prefix'] = getPrefix($contactName);
    $contactDetails['suffix'] = getSuffix($contactName);
    $contactDetails['gender'] = getGender($contact);
    $contactDetails['telecom'] = getTelecom($contact);
    $contactDetails['address'] = getAddress($contact);

    return $contactDetails;
}

function getGeneralPractitioner($resource)
{
    if (isset($resource['generalPractitioner']) && !empty($resource['generalPractitioner'])) {
        return $resource['generalPractitioner'];
    }

    return null;
}

function getResourceType($resource)
{
    if (isset($resource['type']) && !empty($resource['type'])) {
        return $resource['type'];
    }

    return null;
}

function getTypeDetails($type)
{
    $typeDetails = [];

    if (isset($type['coding']) && !empty($type['coding'])) {
        if (isset($type['coding'][0]['system']) && !empty($type['coding'][0]['system'])) {
            $typeDetails['system'] = $type['coding'][0]['system'];
        } else {
            $typeDetails['system'] = '';
        }

        if (isset($type['coding'][0]['code']) && !empty($type['coding'][0]['code'])) {
            $typeDetails['code'] = $type['coding'][0]['code'];
        } else {
            $typeDetails['code'] = 'other';
        }

        if (isset($type['coding'][0]['display']) && !empty($type['coding'][0]['display'])) {
            $typeDetails['display'] = $type['coding'][0]['display'];
        } else {
            $typeDetails['display'] = '';
        }
    } else {
        $typeDetails['system'] = '';
        $typeDetails['code'] = '';
        $typeDetails['display'] = '';
    }

    return $typeDetails;
}

function getOrganizationContactDetails($contact)
{
    $contactDetails = [];

    if (isset($contact['purpose']['coding']) && !empty($resource['purpose']['coding'])) {
        $purpose = $contact['purpose']['coding'];

        if (isset($purpose[0]['system']) && !empty($purpose[0]['system'])) {
            $contactDetails['purposeSystem'] = $purpose[0]['system'];
        } else {
            $contactDetails['purposeSystem'] = '';
        }

        if (isset($purpose[0]['code']) && !empty($purpose[0]['code'])) {
            $contactDetails['purposeCode'] = $purpose[0]['code'];
        } else {
            $contactDetails['purposeCode'] = '';
        }

        if (isset($purpose[0]['display']) && !empty($purpose[0]['display'])) {
            $contactDetails['purposeDisplay'] = $purpose[0]['display'];
        } else {
            $contactDetails['purposeDisplay'] = '';
        }
    } else {
        $contactDetails['purposeSystem'] = '';
        $contactDetails['purposeCode'] = '';
        $contactDetails['purposeDisplay'] = '';
    }

    $contactName = getName($contact);
    $contactDetails['name'] = getFullName($contactName);
    $contactDetails['gender'] = getGender($contact);
    $contactDetails['telecom'] = getTelecom($contact);
    $contactDetails['address'] = getAddress($contact);

    return $contactDetails;
}

function getPartOf($resource)
{
    if (isset($resource['partOf']['reference']) && !empty($resource['partOf']['reference'])) {
        return $resource['partOf']['reference'];
    } else {
        return '';
    }
}

function getOperationalStatus($resource)
{
    if (isset($resource['operationalStatus']['code']) && !empty($resource['operationalStatus']['code'])) {
        return $resource['operationalStatus']['code'];
    } else {
        return '';
    }
}

function getDescription($resource)
{
    if (isset($resource['description']) && !empty($resource['description'])) {
        return $resource['description'];
    } else {
        return '';
    }
}

function getMode($resource)
{
    if (isset($resource['mode']) && !empty($resource['mode'])) {
        return $resource['mode'];
    } else {
        return '';
    }
}

function getPhysicalType($resource)
{
    if (isset($resource['physicalType']['coding'][0]['code']) && !empty($resource['physicalType']['coding'][0]['code'])) {
        return $resource['physicalType']['coding'][0]['code'];
    } else {
        return '';
    }
}

function getPosition($resource)
{
    $position = [
        'longitude' => 0,
        'latitude' => 0,
        'altitude' => 0
    ];

    if (isset($resource['position']) && !empty($resource['position'])) {
        if (isset($resource['position']['longitude']) && !empty($resource['position']['longitude'])) {
            $position['longitude'] = $resource['position']['longitude'];
        }
        if (isset($resource['position']['latitude']) && !empty($resource['position']['latitude'])) {
            $position['latitude'] = $resource['position']['latitude'];
        }
        if (isset($resource['position']['altitude']) && !empty($resource['position']['altitude'])) {
            $position['altitude'] = $resource['position']['altitude'];
        }
    }

    return $position;
}

function getManagingOrganization($resource)
{
    if (isset($resource['managingOrganization']['reference']) && !empty($resource['managingOrganization']['reference'])) {
        return $resource['managingOrganization']['reference'];
    } else {
        return '';
    }
}

function getAvailabilityExceptions($resource)
{
    if (isset($resource['availabilityExceptions']) && !empty($resource['availabilityExceptions'])) {
        return $resource['availabilityExceptions'];
    } else {
        return '';
    }
}

function getServiceClass($extension)
{
    if (is_array($extension) || is_object($extension)) {
        foreach ($extension as $e) {
            if (isset($e['url']) && $e['url'] === 'https://fhir.kemkes.go.id/r4/StructureDefinition/LocationServiceClass') {
                if (isset($e['coding'][0]['code']) && !empty($e['coding'][0]['code'])) {
                    return $e['coding'][0]['code'];
                } else {
                    return null;
                }
            } else {
                return null;
            }
        }
    } else {
        return null;
    }
}

function getOperationHours($resource)
{
    if (isset($resource['hoursOfOperation']) && !empty($resource['hoursOfOperation'])) {
        return $resource['hoursOfOperation'];
    } else {
        return null;
    }
}

function parseOperationHours($operationHours)
{
    $operationHoursDetails = [
        'mon' => false,
        'tue' => false,
        'wed' => false,
        'thu' => false,
        'fri' => false,
        'sat' => false,
        'sun' => false,
        'openingTime' => '00:00:00',
        'closingTime' => '00:00:00'
    ];

    if (isset($operationHours['allDay']) && !empty($operationHours['allDay']) && $operationHours['allDay'] === true) {
        $operationHoursDetails['mon'] = true;
        $operationHoursDetails['tue'] = true;
        $operationHoursDetails['wed'] = true;
        $operationHoursDetails['thu'] = true;
        $operationHoursDetails['fri'] = true;
        $operationHoursDetails['sat'] = true;
        $operationHoursDetails['sun'] = true;
    } else {
        if (isset($operationHours['daysOfWeek']) && !empty($operationHours['daysOfWeek'])) {
            foreach ($operationHours['daysOfWeek'] as $day) {
                $operationHoursDetails[$day] = true;
            }
        }
    }

    if (isset($operationHours['openingTime']) && !empty($operationHours['openingTime'])) {
        $operationHoursDetails['openingTime'] = $operationHours['openingTime'];
    }

    if (isset($operationHours['closingTime']) && !empty($operationHours['closingTime'])) {
        $operationHoursDetails['closingTime'] = $operationHours['closingTime'];
    }

    return $operationHoursDetails;
}

function getStatusHistory($resource)
{
    if (isset($resource['statusHistory']) && !empty($resource['statusHistory'])) {
        return $resource['statusHistory'];
    } else {
        return null;
    }
}

function getClassHistory($resource)
{
    if (isset($resource['classHistory']) && !empty($resource['classHistory'])) {
        return $resource['classHistory'];
    } else {
        return null;
    }
}

function getParticipants($resource)
{
    if (isset($resource['participant']) && !empty($resource['participant'])) {
        return $resource['participant'];
    } else {
        return null;
    }
}

function getReasons($resource)
{
    $reasons = [
        'code' => 0,
        'reference' => ''
    ];

    if (isset($resource['reasonCode']) && !empty($resource['reasonCode'])) {
        $reasons['code'] = $resource['reasonCode'];
    }

    if (isset($resource['reasonReference']) && !empty($resource['reasonReference'])) {
        $reasons['reference'] = $resource['reasonReference'];
    }

    return $reasons;
}

function getDiagnosis($resource)
{
    if (isset($resource['diagnosis']) && !empty($resource['diagnosis'])) {
        return $resource['diagnosis'];
    } else {
        return null;
    }
}

function getBasedOn($resource)
{
    if (isset($resource['basedOn'][0]['reference']) && !empty($resource['basedOn'][0]['reference'])) {
        return $resource['basedOn'][0]['reference'];
    } else {
        return '';
    }
}

function getAccount($resource)
{
    if (isset($resource['account'][0]['reference']) && !empty($resource['account'][0]['reference'])) {
        return $resource['account'][0]['reference'];
    } else {
        return '';
    }
}

function getClass($resource)
{
    if (isset($resource['class']['code']) && !empty($resource['class']['code'])) {
        return $resource['class']['code'];
    } else {
        return '';
    }
}

function getPeriod($resource)
{
    $period = [
        'start' => '1900-01-01',
        'end' => '1900-01-01'
    ];

    if (isset($resource['period']['start']) && !empty($resource['period']['start'])) {
        $period['start'] = $resource['period']['start'];
    }

    if (isset($resource['period']['end']) && !empty($resource['period']['end'])) {
        $period['end'] = $resource['period']['end'];
    }

    return $period;
}

function getLocation($resource)
{
    if (isset($resource['location'][0]['location']['reference']) && !empty($resource['location'][0]['location']['reference'])) {
        return $resource['location'][0]['location']['reference'];
    } else {
        return '';
    }
}

function getIndividual($participant)
{
    if (isset($participant['individual']['reference']) && !empty($participant['individual']['reference'])) {
        return $participant['individual']['reference'];
    } else {
        return '';
    }
}

function getServiceProvider($resource)
{
    if (isset($resource['serviceProvider']['reference']) && !empty($resource['serviceProvider']['reference'])) {
        return $resource['serviceProvider']['reference'];
    } else {
        return '';
    }
}

function getParticipantType($participant)
{
    if (isset($participant['type'][0]['coding'][0]['code']) && !empty($participant['type'][0]['coding'][0]['code'])) {
        return $participant['type'][0]['coding'][0]['code'];
    } else {
        return '';
    }
}

function getDiagnosisDetails($diagnosis)
{
    $diagnosisDetails = [
        'conditionReference' => '',
        'conditionDisplay' => '',
        'use' => '',
        'rank' => 1
    ];

    if (isset($diagnosis['condition']['reference']) && !empty($diagnosis['condition']['reference'])) {
        $diagnosisDetails['conditionReference'] = $diagnosis['condition']['reference'];
    }

    if (isset($diagnosis['condition']['display']) && !empty($diagnosis['condition']['display'])) {
        $diagnosisDetails['conditionDisplay'] = $diagnosis['condition']['display'];
    }

    if (isset($diagnosis['use']['coding'][0]['code']) && !empty($diagnosis['use']['coding'][0]['code'])) {
        $diagnosisDetails['use'] = $diagnosis['use']['coding'][0]['code'];
    }

    if (isset($diagnosis['rank']) && !empty($diagnosis['rank'])) {
        $diagnosisDetails['rank'] = $diagnosis['rank'];
    }

    return $diagnosisDetails;
}

function getCategory($resource)
{
    if (isset($resource['category']) && !empty($resource['category'])) {
        return $resource['category'];
    } else {
        return null;
    }
}

function getCategoryDetails($category)
{
    $categoryDetails = [
        'system' => '',
        'code' => '',
        'display' => ''
    ];

    if (isset($category['coding'][0]['system']) && !empty($category['coding'][0]['system'])) {
        $categoryDetails['system'] = $category['coding'][0]['system'];
    }

    if (isset($category['coding'][0]['code']) && !empty($category['coding'][0]['code'])) {
        $categoryDetails['code'] = $category['coding'][0]['code'];
    }

    if (isset($category['coding'][0]['display']) && !empty($category['coding'][0]['display'])) {
        $categoryDetails['display'] = $category['coding'][0]['display'];
    }

    return $categoryDetails;
}

function getBodySite($resource)
{
    if (isset($resource['bodySite']) && !empty($resource['bodySite'])) {
        return $resource['bodySite'];
    } else {
        return null;
    }
}

function getBodySiteDetails($bodySite)
{
    $bodySiteDetails = [
        'system' => '',
        'code' => '',
        'display' => ''
    ];

    if (isset($bodySite['coding'][0]['system']) && !empty($bodySite['coding'][0]['system'])) {
        $bodySiteDetails['system'] = $bodySite['coding'][0]['system'];
    }

    if (isset($bodySite['coding'][0]['code']) && !empty($bodySite['coding'][0]['code'])) {
        $bodySiteDetails['code'] = $bodySite['coding'][0]['code'];
    }

    if (isset($bodySite['coding'][0]['display']) && !empty($bodySite['coding'][0]['display'])) {
        $bodySiteDetails['display'] = $bodySite['coding'][0]['display'];
    }

    return $bodySiteDetails;
}

function getStage($resource)
{
    if (isset($resource['stage']) && !empty($resource['stage'])) {
        return $resource['stage'];
    } else {
        return null;
    }
}

function getStageDetails($stage)
{
    $stageDetails = [
        'summarySystem' => '',
        'summaryCode' => '',
        'summaryDisplay' => '',
        'typeSystem' => '',
        'typeCode' => '',
        'typeDisplay' => ''
    ];

    if (isset($stage['summary']['coding'][0]['system']) && !empty($stage['summary']['coding'][0]['system'])) {
        $stageDetails['summarySystem'] = $stage['summary']['coding'][0]['system'];
    }

    if (isset($stage['summary']['coding'][0]['code']) && !empty($stage['summary']['coding'][0]['code'])) {
        $stageDetails['summaryCode'] = $stage['summary']['coding'][0]['code'];
    }

    if (isset($stage['summary']['coding'][0]['display']) && !empty($stage['summary']['coding'][0]['display'])) {
        $stageDetails['summaryDisplay'] = $stage['summary']['coding'][0]['display'];
    }

    if (isset($stage['type']['coding'][0]['system']) && !empty($stage['type']['coding'][0]['system'])) {
        $stageDetails['typeSystem'] = $stage['type']['coding'][0]['system'];
    }

    if (isset($stage['type']['coding'][0]['code']) && !empty($stage['type']['coding'][0]['code'])) {
        $stageDetails['typeCode'] = $stage['type']['coding'][0]['code'];
    }

    if (isset($stage['type']['coding'][0]['display']) && !empty($stage['type']['coding'][0]['display'])) {
        $stageDetails['typeDisplay'] = $stage['type']['coding'][0]['display'];
    }

    return $stageDetails;
}

function getAssessment($stage)
{
    if (isset($stage['assessment']) && !empty($stage['assessment'])) {
        return $stage['assessment'];
    } else {
        return null;
    }
}

function getEvidence($resource)
{
    if (isset($resource['evidence']) && !empty($resource['evidence'])) {
        return $resource['evidence'];
    } else {
        return null;
    }
}

function getEvidenceDetails($evidence)
{
    $evidenceDetails = [
        'code' => 0,
        'detailReference' => ''
    ];

    if (isset($evidence['code']['coding'][0]['code']) && !empty($evidence['code']['coding'][0]['code'])) {
        $evidenceDetails['code'] = $evidence['code']['coding'][0]['code'];
    }

    if (isset($evidence['detail'][0]['reference']) && !empty($evidence['detail'][0]['reference'])) {
        $evidenceDetails['detailReference'] = $evidence['detail'][0]['reference'];
    }

    return $evidenceDetails;
}

function getNote($resource)
{
    if (isset($resource['note']) && !empty($resource['note'])) {
        return $resource['note'];
    } else {
        return null;
    }
}

function getNoteDetails($note)
{
    $noteDetails = [
        'author' => '',
        'time' => '1900-01-01',
        'text' => ''
    ];

    if (isset($note['authorReference']) && !empty($note['authorReference'])) {
        $noteDetails['author'] = $note['authorReference'];
    } elseif (isset($note['authorString']) && !empty($note['authorString'])) {
        $noteDetails['author'] = $note['authorString'];
    }

    if (isset($note['time']) && !empty($note['time'])) {
        $noteDetails['time'] = $note['time'];
    }

    if (isset($note['text']) && !empty($note['text'])) {
        $noteDetails['text'] = $note['text'];
    }

    return $noteDetails;
}

function returnAttribute($array, $keys, $defaultValue = null)
{
    $value = $array;
    foreach ($keys as $key) {
        if (isset($value[$key]) && !empty($value[$key])) {
            $value = $value[$key];
        } else {
            return $defaultValue;
        }
    }
    return $value;
}

function parseAndCreate($model, $data, $callback, $foreignKey)
{
    if (is_array($data) || is_object($data)) {
        $dataArray = [];
        foreach ($data as $d) {
            $details = $callback($d);
            if (is_array($details) || is_object($details)) {
                $details = array_merge($details, $foreignKey);
                $dataArray[] = $details;
            }
        }
        $model::insert($dataArray);
    }
}

function parseAndCreateCompound($model, $resource, $attributeCallback, $foreignKey)
{
    $insertData = [];
    foreach ($attributeCallback as $attr => $callback) {
        $data = returnAttribute($resource, [$attr], null);
        if (is_array($data) || is_object($data)) {
            foreach ($data as $d) {
                $details = $callback($d);
                if (is_array($details) || is_object($details)) {
                    $insertData = array_merge($insertData, $details);
                }
            }
        }
    }
    if (!containsOnlyNull($insertData)) {
        $insertData = array_merge($insertData, $foreignKey);
        $model::insert($insertData);
    }
}

function returnEffective($resource)
{
    $effectiveJson[] = [];

    if (isset($resource['effectiveDateTime']) && !empty($resource['effectiveDateTime'])) {
        $effectiveJson['effectiveDateTime'] = $resource['effectiveDateTime'];
    } else if (isset($resource['effectivePeriod']) && !empty($resource['effectivePeriod'])) {
        $effectiveJson['effectivePeriod'] = $resource['effectivePeriod'];
    } else if (isset($resource['effectiveTiming']) && !empty($resource['effectiveTiming'])) {
        $effectiveJson['effectiveTiming'] = $resource['effectiveTiming'];
    } else if (isset($resource['effectiveInstant']) && !empty($resource['effectiveInstant'])) {
        $effectiveJson['effectiveInstant'] = $resource['effectiveInstant'];
    }

    return $effectiveJson;
}

function returnValue($resource)
{
    $valueJson[] = [];
    $valueTypes = ['valueQuantity', 'valueString', 'valueBoolean', 'valueInteger', 'valueRange', 'valueRatio', 'valueSampledData', 'valueTime', 'valueDateTime', 'valuePeriod'];

    foreach ($valueTypes as $v) {
        if (isset($resource[$v]) && !empty($resource[$v])) {
            $valueJson[$v] = $resource[$v];
        }
    }

    return $valueJson;
}

function returnReference($attribute)
{
    if (isset($attribute['reference']) && !empty($attribute['reference'])) {
        return ['reference' => $attribute['reference']];
    } else {
        return null;
    }
}

function returnCodeableConcept($attribute, $prefix = null)
{
    if ($prefix) {
        $prefix = $prefix . '_';
    }
    $codeableConcept = [
        $prefix . 'system' => returnAttribute($attribute, ['coding', 0, 'system'], null),
        $prefix . 'code' => returnAttribute($attribute, ['coding', 0, 'code'], null),
        $prefix . 'display' => returnAttribute($attribute, ['coding', 0, 'display'], null)
    ];

    if (containsOnlyNull($codeableConcept)) {
        return null;
    } else {
        return $codeableConcept;
    }
}

function returnVariableAttribute($resource, $var, $variableAttributes)
{
    $variableAttribute = [];

    foreach ($variableAttributes as $va) {
        $va = $var . $va;
        if (isset($resource[$va]) && !empty($resource[$va])) {
            $variableAttribute[$va] = $resource[$va];
        }
    }

    if (empty($variableAttribute)) {
        return null;
    } else {
        return json_encode($variableAttribute);
    }
}

function returnAnnotation($attribute)
{
    $annotation = [
        'author' => returnVariableAttribute($attribute, 'author', ['String', 'Reference']),
        'time' => returnAttribute($attribute, ['time'], null),
        'text' => returnAttribute($attribute, ['text'], null)
    ];

    return $annotation['text'] === null ? null : $annotation;
}

function returnReferenceRange($attribute)
{
    return [
        'value_low' => returnAttribute($attribute, ['low', 'value']),
        'value_high' => returnAttribute($attribute, ['high', 'value']),
        'unit' => returnAttribute($attribute, ['low', 'unit']) == null ? returnAttribute($attribute, ['high', 'unit'], null) : returnAttribute($attribute, ['low', 'unit'], null),
        'system' => returnAttribute($attribute, ['low', 'system']) == null ? returnAttribute($attribute, ['high', 'system'], null) : returnAttribute($attribute, ['low', 'system'], null),
        'code' => returnAttribute($attribute, ['low', 'code']) == null ? returnAttribute($attribute, ['high', 'code'], null) : returnAttribute($attribute, ['low', 'code'], null),
        'type' => returnAttribute($attribute, ['type', 'coding', 0, 'code']),
        'applies_to' => returnAttribute($attribute, ['appliesTo']),
        'age_low' => returnAttribute($attribute, ['age', 'low', 'value']),
        'age_high' => returnAttribute($attribute, ['age', 'high', 'value']),
        'text' => returnAttribute($attribute, ['text'])
    ];
}

function returnComponent($attribute)
{
    return [
        'code_system' => returnAttribute($attribute, ['code', 'coding', 0, 'system'], ''),
        'code_code' => returnAttribute($attribute, ['code', 'coding', 0, 'code'], ''),
        'code_display' => returnAttribute($attribute, ['code', 'coding', 0, 'display'], ''),
        'value' => returnVariableAttribute($attribute, 'value', ['Quantity', 'CodeableConcept', 'String', 'Boolean', 'Integer', 'Range', 'Ratio', 'SampledData', 'Time', 'DateTime', 'Period']),
        'data_absent_reason' => returnAttribute($attribute, ['dataAbsentReason', 'coding', 0, 'code'], null)
    ];
}

function returnTelecom($attribute)
{
    $telecom = [
        'system' => returnAttribute($attribute, ['system'], 'other'),
        'use' => returnAttribute($attribute, ['use'], 'temp'),
        'value' => returnAttribute($attribute, ['value'], null),
    ];

    if ($telecom['value'] === null) {
        return null;
    } else {
        return $telecom;
    }
}

function returnIdentifier($attribute, $prefix = null)
{
    if ($prefix != null) {
        $prefix = $prefix . '_';
    }

    $identifier = [
        $prefix . 'system' => returnAttribute($attribute, ['system'], ''),
        $prefix . 'use' => returnAttribute($attribute, ['use'], 'temp'),
        $prefix . 'value' => returnAttribute($attribute, ['value'], '')
    ];

    if (containsOnlyNull($identifier)) {
        return null;
    } else {
        return $identifier;
    }
}

function returnAddress($attribute)
{
    $addressDetails = [
        'province' => 0,
        'city' => 0,
        'district' => 0,
        'village' => 0,
        'rw' => 0,
        'rt' => 0
    ];

    $extensionData = returnAttribute($attribute, ['extension', 0, 'extension'], null);

    if (is_array($extensionData) || is_object($extensionData)) {
        foreach ($extensionData as $extension) {
            $url = $extension['url'];
            $value = (int)preg_replace("/[^0-9]/", "", $extension['valueCode']);
            $addressDetails[$url] = $value;
        }
    }

    $line = returnAttribute($attribute, ['line'], null) === null ? returnAttribute($attribute, ['text'], '') : returnAttribute($attribute, ['line'], '');
    if (is_array($line) || is_object($line)) {
        $line = implode(' ', $line);
    }

    return array_merge(
        [
            'use' => returnAttribute($attribute, ['use'], 'temp'),
            'line' => $line,
            'country' => returnAttribute($attribute, ['country'], 'ID'),
            'postal_code' => returnAttribute($attribute, ['postalCode'], ''),
        ],
        $addressDetails
    );
}

function returnHumanName($attribute)
{
    $fullName = returnAttribute($attribute, ['text'], null);

    if ($fullName === null) {
        $givenName = returnAttribute($attribute, ['given'], '');
        if (is_array($givenName) || is_object($givenName)) {
            $givenName = implode(' ', $givenName);
        }
        $familyName = returnAttribute($attribute, ['family'], '');
        $fullName = implode(' ', [$givenName, $familyName]);
    }

    return [
        'name' => $fullName,
        'prefix' => returnAttribute($attribute, ['prefix'], null),
        'suffix' => returnAttribute($attribute, ['suffix'], null)
    ];
}

function returnPatientContact($attribute)
{
    $relationships = returnAttribute($attribute, ['relationship', 0, 'coding'], null);
    $name = returnAttribute($attribute, ['name'], null);

    foreach ($relationships as $r) {
        if ($r['system'] === 'http://terminology.hl7.org/CodeSystem/v2-0131') {
            $relationship = $r['code'];
        } else {
            $relationship = 'U';
        }
    }

    $address = returnAddress($attribute);
    $addressUse = $address['use'];
    $addressLine = $address['line'];
    unset($address['use']);
    unset($address['line']);

    return array_merge(
        [
            'relationship' => $relationship,
            'gender' => returnAttribute($attribute, ['gender'], 'unknown'),
            'address_use' => $addressUse,
            'address_line' => $addressLine

        ],
        returnHumanName($name),
        $address,
    );
}

function returnOrganizationContact($attribute)
{
    $purpose = returnAttribute($attribute, ['purpose'], null);
    $purposeDetails = returnCodeableConcept($purpose);
    if ($purposeDetails === null) {
        $purposeData = [
            'purpose_system' => null,
            'purpose_code' => null,
            'purpose_display' => null
        ];
    } else {
        $purposeData = [
            'purpose_system' => $purposeDetails['system'],
            'purpose_code' => $purposeDetails['code'],
            'purpose_display' => $purposeDetails['display'],
        ];
    }
    $address = returnAddress($attribute);
    $addressUse = $address['use'];
    $addressLine = $address['line'];
    unset($address['use']);
    unset($address['line']);
    try {
        return array_merge(
            $purposeData,
            [
                'name' => returnHumanName($attribute)['name'],
                'address_use' => $addressUse,
                'address_line' => $addressLine
            ],
            $address
        );
    } catch (Exception $e) {
        dd($purposeDetails);
    }
}

function returnPeriod($attribute, $prefix = null)
{
    if ($prefix != null) {
        $prefix = $prefix . '_';
    }

    return [
        $prefix . 'period_start' => parseDateInput(returnAttribute($attribute, ['start'], '1900-01-01')),
        $prefix . 'period_end' => parseDateInput(returnAttribute($attribute, ['end'], null))
    ];
}

function returnStatusHistory($attribute)
{
    return array_merge(
        ['status' => returnAttribute($attribute, ['status'], 'unknown')],
        returnPeriod($attribute)
    );
}

function parseDateInput($date)
{
    if ($date != null) {
        // Create a DateTime object with the input date
        $dateTime = new DateTime($date);

        // Set the desired time zone for the SQL datetime format
        $dateTime->setTimezone(new DateTimeZone('Asia/Jakarta'));

        // Format the date in SQL datetime format
        $sqlDateTime = $dateTime->format('Y-m-d H:i:s');

        return $sqlDateTime;
    } else {
        return null;
    }
}


function containsOnlyNull($input)
{
    return empty(array_filter($input, function ($a) {
        return $a !== null;
    }));
}

function returnDiagnosis($attribute)
{
    $diagnosis = [
        'condition_reference' => returnAttribute($attribute, ['condition', 'reference'], null),
        'condition_display' => returnAttribute($attribute, ['condition', 'display'], null),
        'use' => returnAttribute($attribute, ['use', 'coding', 0, 'code'], null),
        'rank' => returnAttribute($attribute, ['rank'], null)
    ];

    if (containsOnlyNull($diagnosis)) {
        return null;
    } else {
        return $diagnosis;
    }
}

function returnEvidence($attribute)
{
    $evidence = [
        'system' => returnAttribute($attribute, ['code', 0, 'coding', 'system']),
        'code' => returnAttribute($attribute, ['code', 0, 'coding', 'code']),
        'display' => returnAttribute($attribute, ['code', 0, 'coding', 'display']),
        'detail_reference' => returnAttribute($attribute, ['detail', 0, 'reference'])
    ];

    if (containsOnlyNull($evidence)) {
        return null;
    } else {
        return $evidence;
    }
}

function returnParticipant($attribute)
{
    $participant = [
        'type' => returnAttribute($attribute, ['individual', 'reference'], null) === null ? null : returnAttribute($attribute, ['type', 0, 'coding', 0, 'code'], null),
        'individual' => returnAttribute($attribute, ['individual', 'reference'], null)
    ];
    return containsOnlyNull($participant) ? null : $participant;
}

function returnCoding($attribute)
{
    if ($attribute === null) {
        return null;
    } else {
        $coding = [
            'system' => returnAttribute($attribute, ['system'], null),
            'code' => returnAttribute($attribute, ['code'], null),
            'display' => returnAttribute($attribute, ['display'], null)
        ];

        if (containsOnlyNull($coding)) {
            return null;
        } else {
            return $coding;
        }
    }
}

function returnSeries($attribute)
{
    $modality = returnCoding(returnAttribute($attribute, ['modality'], null));
    return [
        'uid' => returnAttribute($attribute, ['uid'], ''),
        'number' => returnAttribute($attribute, ['number'], null),
        'modality_system' => $modality['system'],
        'modality_code' => $modality['code'],
        'modality_display' => $modality['display'],
        'description' => returnAttribute($attribute, ['description'], null),
        'num_instances' => returnAttribute($attribute, ['numberOfInstances'], null),
        'body_site' => returnAttribute($attribute, ['bodySite', 'code'], null),
        'laterality' => returnAttribute($attribute, ['laterality', 'code'], null),
        'started' => returnAttribute($attribute, ['started'], null)
    ];
}

function returnImagingPerformer($attribute)
{
    $actor = returnAttribute($attribute, ['actor', 'reference'], null);
    if ($actor === null) {
        return null;
    } else {
        $function = returnCodeableConcept(returnAttribute($attribute, ['function'], null));
        return [
            'function_system' => $function['system'],
            'function_code' => $function['code'],
            'function_display' => $function['display'],
            'actor' => returnAttribute($attribute, ['actor', 'reference'], null)
        ];
    }
}

function returnImageStudyInstance($attribute)
{
    $uid = returnAttribute($attribute, ['uid'], null);
    $sopClass = returnCoding(returnAttribute($attribute, ['sopClass'], null));
    if ($uid === null) {
        return null;
    } else {
        return [
            'uid' => $uid,
            'sopclass_system' => $sopClass['system'],
            'sopclass_code' => $sopClass['code'],
            'sopclass_display' => $sopClass['display'],
            'number' => returnAttribute($attribute, ['number'], null),
            'title' => returnAttribute($attribute, ['title'], null)
        ];
    }
}

function returnProcedurePerformer($attribute)
{
    return [
        'function' => returnAttribute($attribute, ['function', 'coding', 0, 'code'], null),
        'actor' => returnAttribute($attribute, ['actor', 'reference'], ''),
        'on_behalf_of' => returnAttribute($attribute, ['onBehalfOf', 'reference'], null)
    ];
}

function returnFocalDevice($attribute)
{
    $focalDevice = returnAttribute($attribute, ['focalDevice']);
    $focalDeviceDetails = returnCodeableConcept($focalDevice);
    return array_merge(
        [
            'reference' => returnAttribute($focalDevice, ['manipulated', 'reference'], '')
        ],
        $focalDeviceDetails
    );
}

function returnRatio($attribute, $prefix)
{
    $prefix = $prefix . '_';
    return [
        $prefix . 'numerator_value' => returnAttribute($attribute, ['numerator', 'value'], null),
        $prefix . 'numerator_comparator' => returnAttribute($attribute, ['numerator', 'comparator'], null),
        $prefix . 'numerator_unit' => returnAttribute($attribute, ['numerator', 'unit'], null),
        $prefix . 'numerator_system' => returnAttribute($attribute, ['numerator', 'system'], null),
        $prefix . 'numerator_code' => returnAttribute($attribute, ['numerator', 'code'], null),
        $prefix . 'denominator_value' => returnAttribute($attribute, ['denominator', 'value'], null),
        $prefix . 'denominator_comparator' => returnAttribute($attribute, ['denominator', 'comparator'], null),
        $prefix . 'denominator_unit' => returnAttribute($attribute, ['denominator', 'unit'], null),
        $prefix . 'denominator_system' => returnAttribute($attribute, ['denominator', 'system'], null),
        $prefix . 'denominator_code' => returnAttribute($attribute, ['denominator', 'code'], null),
    ];
}

function returnMedicationIngredient($attribute)
{
    $item = returnAttribute($attribute, ['itemCodeableConcept'], null);
    $itemCodeableConcept = returnCodeableConcept($item, 'item');
    $strength = returnAttribute($attribute, ['strength'], null);
    $strengthData = returnRatio($strength, 'strength');

    return array_merge(
        $itemCodeableConcept,
        $strengthData,
        [
            'item_reference' => returnAttribute($attribute, ['itemReference', 'reference'], null),
            'is_active' => returnAttribute($attribute, ['isActive'], null),

        ]
    );
}

function returnDuration($attribute, $prefix = null)
{
    if ($prefix != null) {
        $prefix = $prefix . '_';
    }

    $duration = [
        $prefix . 'value' => returnAttribute($attribute, ['value'], null),
        $prefix . 'comparator' => returnAttribute($attribute, ['comparator'], null),
        $prefix . 'unit' => returnAttribute($attribute, ['unit'], null),
        $prefix . 'system' => returnAttribute($attribute, ['system'], null),
        $prefix . 'code' => returnAttribute($attribute, ['code'], null),
    ];

    if (containsOnlyNull($duration)) {
        return null;
    } else {
        return $duration;
    }
}

function returnQuantity($attribute, $prefix = null, $simple = false)
{
    if ($prefix != null) {
        $prefix = $prefix . '_';
    }

    $quantity = [
        $prefix . 'value' => returnAttribute($attribute, ['value'], null),
        $prefix . 'unit' => returnAttribute($attribute, ['unit'], null),
        $prefix . 'system' => returnAttribute($attribute, ['system'], null),
        $prefix . 'code' => returnAttribute($attribute, ['code'], null),
    ];

    if ($simple === false) {
        $quantity[] = [$prefix . 'comparator' => returnAttribute($attribute, ['comparator'], null)];
    }

    if (containsOnlyNull($quantity)) {
        return null;
    } else {
        return $quantity;
    }
}

function returnTiming($attribute, $prefix = null)
{
    $code = returnCodeableConcept(returnAttribute($attribute, ['code']), $prefix);

    if ($prefix != null) {
        $prefix = $prefix . '_';
    }

    $timing = merge_array(
        [
            $prefix . 'event' => returnAttribute($attribute, ['event']),
            $prefix . 'repeat' => returnAttribute($attribute, ['repeat'])
        ],
        $code
    );

    if (containsOnlyNull($timing)) {
        return null;
    } else {
        return $timing;
    }
}

function returnDoseRate($attribute, $prefix = null)
{
    $type = returnCodeableConcept(returnAttribute($attribute, ['type']), $prefix);

    $doseRate = array_merge(
        $type,
        [
            'dose' => returnVariableAttribute($attribute, 'dose', ['Range', 'Quantity']),
            'rate' => returnVariableAttribute($attribute, 'rate', ['Ratio', 'Range', 'Quantity'])
        ]
    );

    if (containsOnlyNull($doseRate)) {
        return null;
    } else {
        return $doseRate;
    }
}

function merge_array(...$arrays)
{
    $arr = [];

    try {
        foreach ($arrays as $a) {
            if ($a != null) {
                $arr = array_merge($arr, $a);
            }
        }
    } catch (TypeError $e) {
        dd($a);
    }
    return $arr;
}

function returnAttester($attribute)
{
    $attester = [
        'mode' => returnAttribute($attribute, ['mode'], 'official'),
        'time' => returnAttribute($attribute, ['time']),
        'party' => returnAttribute($attribute, ['party', 'reference'])
    ];

    if (containsOnlyNull($attester)) {
        return null;
    } else {
        return $attester;
    }
}

function relatesTo($attribute)
{
    $relatesTo = [
        'code' => returnAttribute($attribute, ['code'], 'append'),
        'target' => returnVariableAttribute($attribute, 'target', ['Identifier', 'Reference'])
    ];

    if (containsOnlyNull($relatesTo)) {
        return null;
    } else {
        return $relatesTo;
    }
}

function returnNarrative($attribute, $prefix = null)
{
    if ($prefix != null) {
        $prefix = $prefix . '_';
    }

    $narrative = [
        $prefix . 'status' => returnAttribute($attribute, ['status']),
        $prefix . 'div' => returnAttribute($attribute, ['div'])
    ];

    if (containsOnlyNull($narrative)) {
        return null;
    } else {
        return $narrative;
    }
}

function returnFinding($attribute)
{
    $itemCode = returnCodeableConcept(returnAttribute($attribute, ['itemCodeableConcept']));
    $finding = merge_array(
        $itemCode,
        [
            'reference' => returnAttribute($attribute, ['itemReference', 'reference']),
            'basis' => returnAttribute($attribute, ['basis'])
        ]
    );

    if (containsOnlyNull($finding)) {
        return null;
    } else {
        return $finding;
    }
}

function returnPerformer($attribute)
{
    $performer = merge_array(
        returnCodeableConcept(returnAttribute($attribute, ['function']), 'function'),
        ['actor' => returnAttribute($attribute, ['actor', 'reference'])]
    );

    if (containsOnlyNull($performer)) {
        return null;
    } else {
        return $performer;
    }
}

function removeEmptyValues($array)
{
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            // Recursively call the function for nested arrays
            $array[$key] = removeEmptyValues($value);
            if (empty($array[$key])) {
                // Remove keys with empty arrays
                unset($array[$key]);
            }
        } else {
            // Remove null values, empty arrays, empty strings, and keys with empty arrays
            if ($value === null || (is_array($value) && empty($value)) || $value === "") {
                unset($array[$key]);
            }
        }
    }
    return $array;
}



// Helper functions for defined valuesets
/**
 * System: "http://terminology.hl7.org/CodeSystem/v3-ActCode"
 */
function encounterClassDisplay($encounterClass)
{
    switch ($encounterClass) {
        case 'IMP':
            return 'inpatient encounter';
            break;
        case 'AMB':
            return 'ambulatory encounter';
            break;
        case 'OBSENC':
            return 'observation encounter';
            break;
        case 'EMER':
            return 'emergency';
            break;
        case 'VR':
            return 'virtual';
            break;
        case 'HH':
            return 'home health';
            break;
        default:
            return null;
    }
}


/**
 * System: "http://terminology.hl7.org/CodeSystem/v3-MaritalStatus"
 */
function maritalStatusDisplay($maritalCode)
{
    switch ($maritalCode) {
        case 'A':
            return 'Annulled';
            break;
        case 'D':
            return 'Divorced';
            break;
        case 'I':
            return 'Interloctury';
            break;
        case 'L':
            return 'Legally Separated';
            break;
        case 'M':
            return 'Married';
            break;
        case 'C':
            return 'Common Law';
            break;
        case 'P':
            return 'Polygamous';
            break;
        case 'T':
            return 'Domestic partner';
            break;
        case 'U':
            return 'Unmarried';
            break;
        case 'S':
            return 'Never Married';
            break;
        case 'W':
            return 'Widowed';
            break;
        default:
            return null;
    }
}

/**
 * System: "http://terminology.hl7.org/CodeSystem/v2-0131"
 */
function relationshipDisplay($relationshipCode)
{
    switch ($relationshipCode) {
        case 'BP':
            return 'Billing contact person';
            break;
        case 'CP':
            return 'Contact person';
            break;
        case 'EP':
            return 'Emergency contact person';
            break;
        case 'PR':
            return 'Person preparing referral';
            break;
        case 'E':
            return 'Employer';
            break;
        case 'C':
            return 'Emergency Contact';
            break;
        case 'F':
            return 'Federal Agency';
            break;
        case 'I':
            return 'Insurance Company';
            break;
        case 'N':
            return 'Next-of-Kin';
            break;
        case 'S':
            return 'State Agency';
            break;
        case 'O':
            return 'Other';
            break;
        case 'U':
            return 'Unknown';
            break;
        default:
            return null;
            break;
    }
}


/**
 * System: "http://terminology.hl7.org/CodeSystem/service-type"
 */
function serviceTypeDisplay($serviceTypeCode)
{
    try {
        return CodeSystemServiceType::where('code', $serviceTypeCode)->first()->display;
    } catch (Exception $e) {
        Log::error($e);
        return null;
    }
}


/**
 * System: "http://terminology.hl7.org/CodeSystem/v3-ActPriority"
 */
function priorityDisplay($priorityCode)
{
    switch ($priorityCode) {
        case 'A':
            return ' ASAP';
            break;
        case 'CR':
            return 'callback results';
            break;
        case 'CS':
            return 'callback for scheduling';
            break;
        case 'CSP':
            return 'callback placer for scheduling';
            break;
        case 'EL':
            return 'elective';
            break;
        case 'EM':
            return 'emergency';
            break;
        case 'P':
            return 'preop';
            break;
        case 'PRN':
            return 'as needed';
            break;
        case 'R':
            return 'routine';
            break;
        case 'RR':
            return 'rush reporting';
            break;
        case 'S':
            return 'stat';
            break;
        case 'T':
            return 'timing critical';
            break;
        case 'UD':
            return 'use as directed';
            break;
        case 'UR':
            return 'urgent';
            break;
        default:
            return null;
            break;
    }
}


/**
 * Value set: "https://hl7.org/fhir/R4/valueset-encounter-participant-type.html"
 */
function participantType($participantTypeCode)
{
    try {
        return CodeSystemParticipantType::where('code', $participantTypeCode)->first();
    } catch (Exception $e) {
        Log::error($e);
        return null;
    }
}

/**
 * Value set: "https://hl7.org/fhir/R4/valueset-encounter-reason.html"
 */
function encounterReasonDisplay($encounterReasonCode)
{
    try {
        return CodeSystemEncounterReason::where('code', $encounterReasonCode)->first()->display;
    } catch (Exception $e) {
        Log::error($e);
        return null;
    }
}

/**
 * System: "http://terminology.hl7.org/CodeSystem/diagnosis-role"
 */
function diagnosisUseDisplay($diagnosisUseCode)
{
    switch ($diagnosisUseCode) {
        case 'AD':
            return 'Admission diagnosis';
            break;
        case 'DD':
            return 'Discharge diagnosis';
            break;
        case 'CC':
            return 'Chief complaint';
            break;
        case 'CM':
            return 'Comorbidity diagnosis';
            break;
        case 'pre-op':
            return 'pre-op diagnosis';
            break;
        case 'post-op':
            return 'post-op diagnosis';
            break;
        case 'billing':
            return 'billing';
            break;
        default:
            return null;
    }
}

/**
 * System: "http://terminology.hl7.org/CodeSystem/admit-source"
 */
function admitSource($admitSourceCode)
{
    try {
        return CodeSystemAdmitSource::where('code', $admitSourceCode)->first();
    } catch (Exception $e) {
        Log::error($e);
        return null;
    }
}

/**
 * System: "http://terminology.hl7.org/CodeSystem/discharge-disposition"
 */
function dischargeDisposition($dischargeDispositionCode)
{
    try {
        return CodeSystemDischargeDisposition::where('code', $dischargeDispositionCode)->first();
    } catch (Exception $e) {
        Log::error($e);
        return null;
    }
}

/**
 * System: "http://terminology.hl7.org/CodeSystem/condition-clinical"
 */
function clinicalStatus($clinicalStatusCode)
{
    try {
        return CodeSystemClinicalStatus::where('code', $clinicalStatusCode)->first();
    } catch (Exception $e) {
        Log::error($e);
        return null;
    }
}

/**
 * System: "http://terminology.hl7.org/CodeSystem/condition-ver-status"
 */
function verificationStatus($verificationStatusCode)
{
    try {
        return CodeSystemVerificationStatus::where('code', $verificationStatusCode)->first();
    } catch (Exception $e) {
        Log::error($e);
        return null;
    }
}

/**
 * System: "http://hl7.org/fhir/sid/icd-10"
 */
function icd10Display($icd10Code, $language = 'en')
{
    try {
        switch ($language) {
            case 'en':
                return CodeSystemICD10::where('code', $icd10Code)->first()->display_en;
                break;
            case 'id':
                return CodeSystemICD10::where('code', $icd10Code)->first()->display_id;
                break;
            default:
                return CodeSystemICD10::where('code', $icd10Code)->first()->display_en;
                break;
        }
    } catch (Exception $e) {
        Log::error($e);
        return null;
    }
}
