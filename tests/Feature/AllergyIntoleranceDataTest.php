<?php

namespace Tests\Feature;

use App\Models\AllergyIntolerance;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\FhirTest;

class AllergyIntoleranceDataTest extends TestCase
{
    use DatabaseTransactions;
    use FhirTest;

    /**
     * Test apakah user dapat menlihat data alergi pasien
     */
    public function test_users_can_view_allergy_intolerance_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('allergyintolerance');

        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/allergyintolerance/create', $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $response = $this->json('GET', 'api/allergyintolerance/' . $newData['resource_id']);
        $response->assertStatus(200);
    }


    /**
     * Test apakah user dapat membuat data alergi pasien baru
     */
    public function test_users_can_create_new_allergy_intolerance_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('allergyintolerance');

        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/allergyintolerance/create', $data, $headers);
        $response->assertStatus(201);

        $this->assertMainData('allergy_intolerance', $data['allergy_intolerance']);
        $this->assertManyData('allergy_intolerance_identifier', $data['identifier']);
        $this->assertManyData('allergy_intolerance_note', $data['note']);
        $this->assertNestedData('allergy_intolerance_reaction', $data['reaction'], 'reaction_data', [
            [
                'table' => 'allergy_react_manifest',
                'data' => 'manifestation'
            ],
            [
                'table' => 'allergy_react_note',
                'data' => 'note'
            ]
        ]);
        $this->assertDatabaseHas('resource_content', [
            'res_ver' => 1,
            'res_text' => '{"resourceType":"AllergyIntolerance","identifier":[{"system":"http:\/\/sys-ids.kemkes.go.id\/allergy\/10000004","use":"official","value":"5234342"}],"clinicalStatus":{"coding":[{"system":"http:\/\/terminology.hl7.org\/CodeSystem\/allergyintolerance-clinical","code":"active","display":"Active"}]},"verificationStatus":{"coding":[{"system":"http:\/\/terminology.hl7.org\/CodeSystem\/allergyintolerance-verification","code":"unconfirmed","display":"Unconfirmed"}]},"type":"allergy","category":["food"],"criticality":"low","code":{"coding":[{"system":"http:\/\/snomed.info\/sct","code":"89811004","display":"Gluten (substance)"}]},"patient":{"reference":"Patient\/100000030009"},"encounter":{"reference":"Encounter\/3dedcec9-885d-435e-9ac5-58853cb216bb"},"recordedDate":"2023-11-01T11:16:01+07:00","recorder":{"reference":"Practitioner\/1000400104"},"asserter":{"reference":"Practitioner-1000400104"},"lastOcurrence":"2023-11-02T10:31:00+07:00","note":[{"authorString":"Dokter Bronsig","authorReference":{"reference":"Practitioner\/1000400104"},"time":"2023-11-01T11:31:00+07:00","text":"# Catatan<br>## Subbab"}],"reaction":[{"substance":{"coding":[{"system":"http:\/\/terminology.kemkes.go.id\/CodeSystem\/clinical-term","code":"AL000024","display":"Kuning telur"}]},"manifestation":[{"coding":[{"system":"http:\/\/snomed.info\/sct","code":"195967001","display":"Asthma"}]}],"description":"Orang ini alergi kuning telur.","onset":"2023-11-02T10:45:00+07:00","severity":"mild","exposureRoute":{"coding":[{"system":"http:\/\/snomed.info\/sct","code":"6064005","display":"Topical route"}]},"note":[{"authorString":"Dokter Bronsig","authorReference":{"reference":"Practitioner\/1000400104"},"time":"2023-11-01T11:31:00+07:00","text":"# Catatan<br>## Subbab"}]}],"onsetDateTime":"2023-10-31T11:43:23+07:00","onsetAge":{"value":22,"comparator":"<","unit":"years","system":"http:\/\/unitsofmeasure.org","code":"a"},"onsetPeriod":{"start":"2023-11-15T14:56:34+07:00","end":"2023-11-15T15:56:34+07:00"},"onsetRange":{"low":{"value":10,"unit":"years","system":"http:\/\/unitsofmeasure.org","code":"a"},"high":{"value":25,"unit":"years","system":"http:\/\/unitsofmeasure.org","code":"a"}},"onsetString":"sehari-hari"}'
        ]);
    }


    /**
     * Test apakah user dapat memperbarui data alergi pasien
     */
    public function test_users_can_update_allergy_intolerance_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('allergyintolerance');
        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/allergyintolerance/create', $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $data['allergy_intolerance']['id'] = $newData['id'];
        $data['allergy_intolerance']['resource_id'] = $newData['resource_id'];
        $data['allergy_intolerance']['type'] = 'intolerance';
        $data['identifier'][0]['id'] = $newData['identifier'][0]['id'];
        $data['identifier'][0]['allergy_id'] = $newData['identifier'][0]['allergy_id'];
        $data['identifier'][0]['value'] = "5234341";

        $data['identifier'][] = [
            'system' => 'http://loinc.org',
            'use' => 'official',
            'value' => '1234567890'
        ];

        $response = $this->json('PUT', '/api/allergyintolerance/' . $newData['resource_id'], $data, $headers);
        $response->assertStatus(200);

        $this->assertMainData('allergy_intolerance', $data['allergy_intolerance']);
        $this->assertManyData('allergy_intolerance_identifier', $data['identifier']);
        $this->assertManyData('allergy_intolerance_note', $data['note']);
        $this->assertNestedData('allergy_intolerance_reaction', $data['reaction'], 'reaction_data', [
            [
                'table' => 'allergy_react_manifest',
                'data' => 'manifestation'
            ],
            [
                'table' => 'allergy_react_note',
                'data' => 'note'
            ]
        ]);
    }
}
