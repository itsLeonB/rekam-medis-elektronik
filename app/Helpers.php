<?php

use App\Models\KotaKabupaten;

function getActive($resource)
{
    if (isset($resource['active']) && !empty($resource['active'])) {
        return $resource['active'];
    } else {
        return true;
    }
}

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
        return '';
    }

    foreach ($nameData as $name) {

        $displayName = '';
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
        return '';
    }

    foreach ($nameData as $name) {

        $displayName = '';
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
                            return 0;
                        }
                    }
                } elseif (isset($e['valueAddress']) && !empty($e['valueAddress'])) {
                    if (isset($e['valueAddress']['city']) && !empty($e['valueAddress']['city'])) {
                        $city = $e['valueAddress']['city'];
                        $kotaKabupaten = KotaKabupaten::where('nama_wilayah', $city)
                            ->orWhere('nama_wilayah', 'Kota ' . $city)
                            ->orWhere('nama_wilayah', 'Kabupaten ' . $city)
                            ->first();
                        if ($kotaKabupaten) {
                            return $kotaKabupaten->kode_wilayah;
                        } else {
                            return 0;
                        }
                    } else {
                        return 0;
                    }
                }
            } else {
                return 0;
            }
        }
    } else {
        return 0;
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

function getAlias($resource)
{
    if (isset($resource['alias'][0]) && !empty($resource['alias'][0])) {
        return $resource['alias'][0];
    } else {
        return '';
    }
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
                    return '0';
                }
            } else {
                return '0';
            }
        }
    } else {
        return '0';
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
