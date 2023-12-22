<?php

namespace App\Fhir;

use App\Models\Fhir\BackboneElements\EncounterHospitalization;
use App\Models\Fhir\BackboneElements\EncounterLocation;
use App\Models\Fhir\BackboneElements\EncounterParticipant;
use App\Models\Fhir\BackboneElements\EncounterStatusHistory;
use App\Models\Fhir\BackboneElements\LocationHoursOfOperation;
use App\Models\Fhir\BackboneElements\LocationPosition;
use App\Models\Fhir\BackboneElements\OrganizationContact;
use App\Models\Fhir\BackboneElements\PatientCommunication;
use App\Models\Fhir\BackboneElements\PatientContact;
use App\Models\Fhir\BackboneElements\PatientLink;
use App\Models\Fhir\BackboneElements\PractitionerQualification;
use App\Models\Fhir\Datatypes\Address;
use App\Models\Fhir\Datatypes\Attachment;
use App\Models\Fhir\Datatypes\CodeableConcept;
use App\Models\Fhir\Datatypes\Coding;
use App\Models\Fhir\Datatypes\ComplexExtension;
use App\Models\Fhir\Datatypes\ContactPoint;
use App\Models\Fhir\Datatypes\Duration;
use App\Models\Fhir\Datatypes\Extension;
use App\Models\Fhir\Datatypes\HumanName;
use App\Models\Fhir\Datatypes\Identifier;
use App\Models\Fhir\Datatypes\Period;
use App\Models\Fhir\Datatypes\Reference;
use App\Models\Fhir\Resource;
use App\Models\Fhir\Resources\Encounter;
use App\Models\Fhir\Resources\Location;
use App\Models\Fhir\Resources\Organization;
use App\Models\Fhir\Resources\Patient;
use App\Models\Fhir\Resources\Practitioner;
use Exception;
use Illuminate\Support\Facades\Log;

class Processor
{
    public function generateEncounter(Resource $resource, string $jsonData): Encounter
    {
        $array = $this->readJsonResource($jsonData);

        $encounter = new Encounter([
            'status' => $array['status'] ?? null,
        ]);

        $encounter = $resource->encounter()->save($encounter);

        $this->saveManyIdentifier($encounter, 'identifier', $array['identifier'] ?? null);

        if (!empty($array['statusHistory'])) {
            foreach ($array['statusHistory'] as $sh) {
                $statusHistory = new EncounterStatusHistory([
                    'status' => $sh['status'] ?? null,
                ]);
                $statusHistory = $encounter->statusHistory()->save($statusHistory);
                $this->generatePeriod($statusHistory, 'period', $sh['period'] ?? null);
            }
        }

        $this->generateCoding($encounter, 'class', $array['class'] ?? null);

        if (!empty($array['classHistory'])) {
            foreach ($array['classHistory'] as $ch) {
                $classHistory = new Coding();
                $classHistory = $encounter->classHistory()->save($classHistory);
                $this->generateCoding($classHistory, 'class', $ch['class'] ?? null);
                $this->generatePeriod($classHistory, 'period', $ch['period'] ?? null);
            }
        }

        $this->saveManyCodeableConcept($encounter, 'type', $array['type'] ?? null);
        $this->generateCodeableConcept($encounter, 'serviceType', $array['serviceType'] ?? null);
        $this->generateCodeableConcept($encounter, 'priority', $array['priority'] ?? null);
        $this->generateReference($encounter, 'subject', $array['subject'] ?? null);
        $this->saveManyReference($encounter, 'episodeOfCare', $array['episodeOfCare'] ?? null);
        $this->saveManyReference($encounter, 'basedOn', $array['basedOn'] ?? null);

        if (!empty($array['participant'])) {
            foreach ($array['participant'] as $p) {
                $participant = new EncounterParticipant();
                $participant = $encounter->participant()->save($participant);
                $this->saveManyCodeableConcept($participant, 'type', $p['type'] ?? null);
                $this->generatePeriod($participant, 'period', $p['period'] ?? null);
                $this->generateReference($participant, 'individual', $p['individual'] ?? null);
            }
        }

        $this->saveManyReference($encounter, 'appointment', $array['appointment'] ?? null);
        $this->generatePeriod($encounter, 'period', $array['period'] ?? null);
        $this->generateDuration($encounter, 'length', $array['length'] ?? null);
        $this->saveManyCodeableConcept($encounter, 'reasonCode', $array['reasonCode'] ?? null);
        $this->saveManyReference($encounter, 'reasonReference', $array['reasonReference'] ?? null);

        if (!empty($array['hospitalization'])) {
            $hospitalization = new EncounterHospitalization();
            $hospitalization = $encounter->hospitalization()->save($hospitalization);
            $this->generateIdentifier($hospitalization, 'preAdmissionIdentifier', $array['hospitalization']['preAdmissionIdentifier'] ?? null);
            $this->generateReference($hospitalization, 'origin', $array['hospitalization']['origin'] ?? null);
            $this->generateCodeableConcept($hospitalization, 'admitSource', $array['hospitalization']['admitSource'] ?? null);
            $this->generateCodeableConcept($hospitalization, 'reAdmission', $array['hospitalization']['reAdmission'] ?? null);
            $this->saveManyCodeableConcept($hospitalization, 'dietPreference', $array['hospitalization']['dietPreference'] ?? null);
            $this->saveManyCodeableConcept($hospitalization, 'specialCourtesy', $array['hospitalization']['specialCourtesy'] ?? null);
            $this->saveManyCodeableConcept($hospitalization, 'specialArrangement', $array['hospitalization']['specialArrangement'] ?? null);
            $this->generateReference($hospitalization, 'destination', $array['hospitalization']['destination'] ?? null);
            $this->generateCodeableConcept($hospitalization, 'dischargeDisposition', $array['hospitalization']['dischargeDisposition'] ?? null);
        }

        if (!empty($array['location'])) {
            foreach ($array['location'] as $l) {
                $location = new EncounterLocation([
                    'status' => $array['location']['status'] ?? null,
                ]);
                $location = $encounter->location()->save($location);
                $this->generateReference($location, 'location', $l['location'] ?? null);
                $this->generateCodeableConcept($location, 'physicalType', $l['physicalType'] ?? null);
                $this->generatePeriod($location, 'period', $l['period'] ?? null);

                if (!empty($l['extension'])) {
                    foreach ($l['extension'] as $e) {
                        if ($e['url'] == 'https://fhir.kemkes.go.id/r4/StructureDefinition/ServiceClass') {
                            $this->generateComplexExtension($location, 'serviceClass', $e);
                        }
                    }
                }
            }
        }

        $this->generateReference($encounter, 'serviceProvider', $array['serviceProvider'] ?? null);
        $this->generateReference($encounter, 'partOf', $array['partOf'] ?? null);

        return $encounter;
    }

    public function generateDuration($model, $attribute, $array)
    {
        if (!empty($array)) {
            $duration = new Duration($array);
            $model->$attribute()->save($duration);
        } else {
            return null;
        }
    }

    public function generatePatient(Resource $resource, string $jsonData): Patient
    {
        $array = $this->readJsonResource($jsonData);

        $patient = new Patient([
            'active' => $array['active'] ?? null,
            'gender' => $array['gender'] ?? 'unknown',
            'birth_date' => $array['birthDate'] ?? null,
            'deceased_boolean' => $array['deceasedBoolean'] ?? null,
            'deceased_datetime' => $array['deceasedDateTime'] ?? null,
            'multiple_birth_boolean' => $array['multipleBirthBoolean'] ?? null,
            'multiple_birth_integer' => $array['multipleBirthInteger'] ?? null,
        ]);

        $patient = $resource->patient()->save($patient);

        $this->saveManyIdentifier($patient, 'identifier', $array['identifier'] ?? null);
        $this->saveManyHumanName($patient, 'name', $array['name'] ?? null);
        $this->saveManyContactPoint($patient, 'telecom', $array['telecom'] ?? null);
        $this->saveManyAddress($patient, 'address', $array['address'] ?? null);
        $this->generateCodeableConcept($patient, 'maritalStatus', $array['maritalStatus'] ?? null);
        $this->saveManyAttachment($patient, 'photo', $array['photo'] ?? null);

        if (!empty($array['contact'])) {
            foreach ($array['contact'] as $c) {
                $contact = new PatientContact([
                    'gender' => $c['gender'] ?? null
                ]);

                $contact = $patient->contact()->save($contact);

                $this->generateCodeableConcept($contact, 'relationship', $c['relationship'] ?? null);
                $this->generateHumanName($contact, 'name', $c['name'] ?? null);
                $this->saveManyContactPoint($contact, 'telecom', $c['telecom'] ?? null);
                $this->generateAddress($contact, 'address', $c['address'] ?? null);
                $this->generateReference($contact, 'organization', $c['organization'] ?? null);
                $this->generatePeriod($contact, 'period', $c['period'] ?? null);
            }
        }

        if (!empty($array['communication'])) {
            foreach ($array['communication'] as $c) {
                $communication = new PatientCommunication([
                    'preferred' => $c['preferred'] ?? null
                ]);
                $communication = $patient->communication()->save($communication);
                $this->generateCodeableConcept($communication, 'language', $c['language'] ?? null);
            }
        }

        $this->saveManyReference($patient, 'generalPractitioner', $array['generalPractitioner'] ?? null);
        $this->generateReference($patient, 'managingOrganization', $array['managingOrganization'] ?? null);

        if (!empty($array['link'])) {
            foreach ($array['link'] as $l) {
                $link = new PatientLink([
                    'type' => $l['type'] ?? null,
                ]);
                $link = $patient->link()->save($link);
                $this->generateReference($link, 'other', $l['other'] ?? null);
            }
        }

        if (!empty($array['extension'])) {
            foreach ($array['extension'] as $e) {
                if ($e['url'] == 'https://fhir.kemkes.go.id/r4/StructureDefinition/patient-citizenship') {
                    $this->generateComplexExtension($patient, 'citizenship', $e ?? null);
                } else {
                    $this->generateExtension($patient, 'extensions', $e ?? null);
                }
            }
        }

        return $patient;
    }

    public function generatePractitioner(Resource $resource, string $jsonData): Practitioner
    {
        $array = $this->readJsonResource($jsonData);

        $practitioner = new Practitioner([
            'active' => $array['active'] ?? null,
            'gender' => $array['gender'] ?? null,
            'birth_date' => $array['birthDate'] ?? null
        ]);

        $practitioner = $resource->practitioner()->save($practitioner);

        $this->saveManyIdentifier($practitioner, 'identifier', $array['identifier'] ?? null);
        $this->saveManyHumanName($practitioner, 'name', $array['name'] ?? null);
        $this->saveManyContactPoint($practitioner, 'telecom', $array['telecom'] ?? null);
        $this->saveManyAddress($practitioner, 'address', $array['address'] ?? null);
        $this->saveManyAttachment($practitioner, 'photo', $array['photo'] ?? null);

        if (!empty($array['qualification'])) {
            foreach ($array['qualification'] as $q) {
                $qualification = new PractitionerQualification($q);
                $qualification = $practitioner->qualification()->save($qualification);
                $this->saveManyIdentifier($qualification, 'identifier', $q['identifier'] ?? null);
                $this->generateCodeableConcept($qualification, 'code', $q['code'] ?? null);
                $this->generatePeriod($qualification, 'period', $q['period'] ?? null);
                $this->generateReference($qualification, 'issuer', $q['issuer'] ?? null);
            }
        }

        $this->saveManyCodeableConcept($practitioner, 'communication', $array['communication'] ?? null);

        return $practitioner;
    }

    public function saveManyAttachment($model, $attribute, $array)
    {
        if (!empty($array)) {
            foreach ($array as $a) {
                $this->generateAttachment($model, $attribute, $a);
            }
        }
    }

    public function generateAttachment($model, $attribute, $array)
    {
        if (!empty($array)) {
            $attachment = new Attachment([
                'content_type' => $array['contentType'] ?? null,
                'language' => $array['language'] ?? null,
                'data' => $array['data'] ?? null,
                'url' => $array['url'] ?? null,
                'size' => $array['size'] ?? null,
                'hash' => $array['hash'] ?? null,
                'title' => $array['title'] ?? null,
                'creation' => $array['creation'] ?? null,
                'attr_type' => $attribute
            ]);
            $model->$attribute()->save($attachment);
        } else {
            return null;
        }
    }

    public function saveManyHumanName($model, $attribute, $array)
    {
        if (!empty($array)) {
            foreach ($array as $h) {
                $this->generateHumanName($model, $attribute, $h);
            }
        }
    }

    public function generateLocation(Resource $resource, string $jsonData): Location
    {
        $array = $this->readJsonResource($jsonData);

        $location = new Location([
            'status' => $array['status'] ?? null,
            'name' => $array['name'] ?? null,
            'alias' => $array['alias'] ?? null,
            'description' => $array['description'] ?? null,
            'mode' => $array['mode'] ?? null,
            'availability_exceptions' => $array['availabilityExceptions'] ?? null,
        ]);

        $location = $resource->location()->save($location);

        $this->saveManyIdentifier($location, 'identifier', $array['identifier'] ?? null);
        $this->generateCoding($location, 'operationalStatus', $array['operationalStatus'] ?? null);
        $this->saveManyCodeableConcept($location, 'type', $array['type'] ?? null);
        $this->saveManyContactPoint($location, 'telecom', $array['telecom'] ?? null);
        $this->generateAddress($location, 'address', $array['address'] ?? null);
        $this->generateCodeableConcept($location, 'physicalType', $array['physicalType'] ?? null);

        if (!empty($array['position'])) {
            $position = new LocationPosition($array['position']);
            $location->position()->save($position);
        }

        $this->generateReference($location, 'managingOrganization', $array['managingOrganization'] ?? null);
        $this->generateReference($location, 'partOf', $array['partOf'] ?? null);

        if (!empty($array['hoursOfOperation'])) {
            foreach ($array['hoursOfOperation'] as $h) {
                $hoursOfOperation = new LocationHoursOfOperation($h);
                $location->hoursOfOperation()->save($hoursOfOperation);
            }
        }

        $this->saveManyReference($location, 'endpoint', $array['endpoint'] ?? null);
        $this->generateExtension($location, 'extension', $array['extension'] ?? null);

        return $location;
    }

    public function generateOrganization(Resource $resource, string $jsonData): Organization
    {
        $array = $this->readJsonResource($jsonData);

        // dd($array);

        $organization = new Organization([
            'active' => $array['active'] ?? null,
            'name' => $array['name'] ?? null,
            'alias' => $array['alias'] ?? null
        ]);

        $organization = $resource->organization()->save($organization);

        $this->saveManyIdentifier($organization, 'identifier', $array['identifier'] ?? null);
        $this->saveManyCodeableConcept($organization, 'type', $array['type'] ?? null);
        $this->saveManyContactPoint($organization, 'telecom', $array['telecom'] ?? null);
        $this->saveManyAddress($organization, 'address', $array['address'] ?? null);
        $this->generateReference($organization, 'partOf', $array['partOf'] ?? null);
        $this->saveManyReference($organization, 'endpoint', $array['endpoint'] ?? null);

        if (!empty($array['contact'])) {
            foreach ($array['contact'] as $c) {
                $contact = new OrganizationContact();
                $contact = $organization->contact()->save($contact);
                $this->generateCodeableConcept($contact, 'purpose', $c['purpose'] ?? null);
                $this->generateHumanName($contact, 'name', $c['name'] ?? null);
                $this->saveManyContactPoint($contact, 'telecom', $c['telecom'] ?? null);
                $this->generateAddress($contact, 'address', $c['address'] ?? null);
            }
        }
        return $organization;
    }

    private function generateHumanName($model, $attribute, $array)
    {
        if (!empty($array)) {
            $humanName = new HumanName([
                'use' => $array['use'] ?? null,
                'text' => $array['text'] ?? null,
                'family' => $array['family'] ?? null,
                'given' => $array['given'] ?? null,
                'prefix' => $array['prefix'] ?? null,
                'suffix' => $array['suffix'] ?? null,
            ]);
            $humanName = $model->$attribute()->save($humanName);
            $this->generatePeriod($model, $attribute, $array['period'] ?? null);
            return $humanName;
        } else {
            return null;
        }
    }

    private function saveManyReference($model, $attribute, $array)
    {
        if (!empty($array)) {
            foreach ($array as $r) {
                $this->generateReference($model, $attribute, $r);
            }
        }
    }

    private function saveManyAddress($model, $attribute, $array)
    {
        if (!empty($array)) {
            foreach ($array as $a) {
                $this->generateAddress($model, $attribute, $a);
            }
        }
    }

    private function generateAddress($model, $attribute, $array)
    {
        if (!empty($array)) {
            $address = new Address([
                'use' => $array['use'] ?? null,
                'type' => $array['type'] ?? null,
                'text' => $array['text'] ?? null,
                'line' => $array['line'] ?? null,
                'city' => $array['city'] ?? null,
                'district' => $array['district'] ?? null,
                'state' => $array['state'] ?? null,
                'postal_code' => $array['postal_code'] ?? null,
                'country' => $array['country'] ?? null,
                'attr_type' => $attribute
            ]);

            $address = $model->$attribute()->save($address);
            $this->generatePeriod($address, 'period', $array['period'] ?? null);

            if (!empty($array['extension'])) {
                foreach ($array['extension'] as $e) {
                    $this->generateComplexExtension($address, 'complexExtension', $e);
                }
            }
        } else {
            return null;
        }
    }

    private function generateComplexExtension($model, $attribute, $array)
    {
        if (!empty($array)) {
            $complexExtension = new ComplexExtension([
                'url' => $array['url'] ?? null,
                'extension' => [],
                'attr_type' => $attribute
            ]);
            $complexExtension = $model->$attribute()->save($complexExtension);

            if (!empty($array['extension'])) {
                $extensions = [];
                foreach ($array['extension'] as $e) {
                    $extensions[] = $e['url'];
                    $this->generateExtension($complexExtension, 'extension', $e);
                }
                $complexExtension->extension = $extensions;
                $complexExtension->save();
            }
        }
    }

    private function generateExtension($model, $attribute, $array)
    {
        if (!empty($array)) {
            $extension = new Extension([
                'url' => $array['url'] ?? null,
                'value_base_64_binary' => $array['valueBase64Binary'] ?? null,
                'value_boolean' => $array['valueBoolean'] ?? null,
                'value_canonical' => $array['valueCanonical'] ?? null,
                'value_code' => $array['valueCode'] ?? null,
                'value_date' => $array['valueDate'] ?? null,
                'value_date_time' => $array['valueDateTime'] ?? null,
                'value_decimal' => $array['valueDecimal'] ?? null,
                'value_id' => $array['valueId'] ?? null,
                'value_instant' => $array['valueInstant'] ?? null,
                'value_integer' => $array['valueInteger'] ?? null,
                'value_markdown' => $array['valueMarkdown'] ?? null,
                'value_oid' => $array['valueOid'] ?? null,
                'value_positive_int' => $array['valuePositiveInt'] ?? null,
                'value_string' => $array['valueString'] ?? null,
                'value_time' => $array['valueTime'] ?? null,
                'value_unsigned_int' => $array['valueUnsignedInt'] ?? null,
                'value_uri' => $array['valueUri'] ?? null,
                'value_url' => $array['valueUrl'] ?? null,
                'value_uuid' => $array['valueUuid'] ?? null,
                'attr_type' => $attribute
            ]);

            $extension = $model->$attribute($array['url'] ?? null)->save($extension);
            $this->generateAddress($extension, 'valueAddress', $array['valueAddress'] ?? null);
            // $this->generateAge($extension, 'valueAge', $array['valueAge'] ?? null);
            // $this->generateAnnotation($extension, 'valueAnnotation', $array['valueAnnotation'] ?? null);
            // $this->generateAttachment($extension, 'valueAttachment', $array['valueAttachment'] ?? null);
            $this->generateCodeableConcept($extension, 'valueCodeableConcept', $array['valueCodeableConcept'] ?? null);
            $this->generateCoding($extension, 'valueCoding', $array['valueCoding'] ?? null);
            $this->generateContactPoint($extension, 'valueContactPoint', $array['valueContactPoint'] ?? null);
            // $this->generateCount($extension, 'valueCount', $array['valueCount'] ?? null);
            // $this->generateDistance($extension, 'valueDistance', $array['valueDistance'] ?? null);
            // $this->generateDuration($extension, 'valueDuration', $array['valueDuration'] ?? null);
            $this->generateHumanName($extension, 'valueHumanName', $array['valueHumanName'] ?? null);
            $this->generateIdentifier($extension, 'valueIdentifier', $array['valueIdentifier'] ?? null);
            $this->generatePeriod($extension, 'valuePeriod', $array['valuePeriod'] ?? null);
            // $this->generateQuantity($extension, 'valueQuantity', $array['valueQuantity'] ?? null);
            // $this->generateRange($extension, 'valueRange', $array['valueRange'] ?? null);
            // $this->generateRatio($extension, 'valueRatio', $array['valueRatio'] ?? null);
            // $this->generateSampledData($extension, 'valueSampledData', $array['valueSampledData'] ?? null);
            // $this->generateSignature($extension, 'valueSignature', $array['valueSignature'] ?? null);
            // $this->generateTiming($extension, 'valueTiming', $array['valueTiming'] ?? null);
            // $this->generateContactDetail($extension, 'valueContactDetail', $array['valueContactDetail'] ?? null);
            // $this->generateContributor($extension, 'valueContributor', $array['valueContributor'] ?? null);
            // $this->generateDataRequirement($extension, 'valueDataRequirement', $array['valueDataRequirement'] ?? null);
            // $this->generateExpression($extension, 'valueExpression', $array['valueExpression'] ?? null);
            // $this->generateParameterDefinition($extension, 'valueParameterDefinition', $array['valueParameterDefinition'] ?? null);
            // $this->generateRelatedArtifact($extension, 'valueRelatedArtifact', $array['valueRelatedArtifact'] ?? null);
            // $this->generateTriggerDefinition($extension, 'valueTriggerDefinition', $array['valueTriggerDefinition'] ?? null);
            // $this->generateUsageContext($extension, 'valueUsageContext', $array['valueUsageContext'] ?? null);
            // $this->generateDosage($extension, 'valueDosage', $array['valueDosage'] ?? null);
            // $this->generateMeta($extension, 'valueMeta', $array['valueMeta'] ?? null);
            $this->generateReference($extension, 'valueReference', $array['valueReference'] ?? null);
        }
    }

    private function saveManyContactPoint($model, $attribute, $array)
    {
        if (!empty($array)) {
            foreach ($array as $c) {
                $this->generateContactPoint($model, $attribute, $c);
            }
        }
    }

    private function generateContactPoint($model, $attribute, $array)
    {
        if (!empty($array)) {
            $contactPoint = new ContactPoint([
                'system' => $array['system'] ?? null,
                'value' => $array['value'] ?? null,
                'use' => $array['use'] ?? null,
                'rank' => $array['rank'] ?? null,
                'attr_type' => $attribute
            ]);
            $contactPoint = $model->$attribute()->save($contactPoint);
            $this->generatePeriod($model, $attribute, $array['period'] ?? null);
        } else {
            return null;
        }
    }

    private function saveManyCodeableConcept($model, $attribute, $array)
    {
        if (!empty($array)) {
            foreach ($array as $c) {
                $this->generateCodeableConcept($model, $attribute, $c);
            }
        }
    }

    private function saveManyIdentifier($model, $attribute, $array)
    {
        if (!empty($array)) {
            foreach ($array as $i) {
                $this->generateIdentifier($model, $attribute, $i);
            }
        }
    }

    private function generateIdentifier($model, $attribute, $array)
    {
        if (!empty($array)) {
            $identifier = new Identifier([
                'use' => $array['use'] ?? null,
                'system' => $array['system'] ?? null,
                'value' => $array['value'] ?? null,
                'attr_type' => $attribute
            ]);

            $identifier = $model->$attribute()->save($identifier);

            $this->generateCodeableConcept($identifier, 'type', $array['type'] ?? null);
            $this->generatePeriod($identifier, 'period', $array['period'] ?? null);
            $this->generateReference($identifier, 'assigner', $array['assigner'] ?? null);
        } else {
            return null;
        }
    }

    private function generateReference($model, $attribute, $array)
    {
        if (!empty($array)) {
            $reference = new Reference([
                'reference' => $array['reference'] ?? null,
                'type' => $array['type'] ?? null,
                'display' => $array['display'] ?? null,
                'attr_type' => $attribute
            ]);
            $reference = $model->$attribute()->save($reference);
            $this->generateIdentifier($reference, 'identifier', $array['identifier'] ?? null);
        } else {
            return null;
        }
    }

    private function generatePeriod($model, $attribute, $array)
    {
        if (!empty($array)) {
            $period = new Period(array_merge(
                $array,
                ['attr_type' => $attribute]
            ));
            $period = $model->$attribute()->save($period);
        } else {
            return null;
        }
    }

    private function generateCodeableConcept($model, $attribute, $array)
    {
        if (!empty($array)) {
            $codeableConcept = new CodeableConcept([
                'text' => $array['text'] ?? null,
                'attr_type' => $attribute
            ]);
            $codeableConcept = $model->$attribute()->save($codeableConcept);
            if (!empty($array['coding'])) {
                foreach ($array['coding'] as $c) {
                    $this->generateCoding($codeableConcept, 'coding', $c);
                }
            }
        } else {
            return null;
        }
    }

    private function generateCoding($model, $attribute, $array)
    {
        if (!empty($array)) {
            $coding = new Coding(array_merge(
                $array,
                ['attr_type' => $attribute]
            ));
            $model->$attribute()->save($coding);
        } else {
            return null;
        }
    }

    private function readJsonResource(string $jsonData): array
    {
        try {
            $array = json_decode($jsonData, true);
            return $array;
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw new Exception;
        }
    }
}
