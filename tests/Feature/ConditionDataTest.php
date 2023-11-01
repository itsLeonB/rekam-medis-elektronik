<?php

namespace Tests\Feature;

use App\Models\Condition;
use App\Models\Encounter;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ConditionDataTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test apakah user dapat menlihat data kondisi pasien
     */
    public function test_users_can_view_condition_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getConditionTestData();

        $resource = Resource::create(
            [
                'satusehat_id' => '000001',
                'res_type' => 'Condition',
                'res_ver' => 1
            ]
        );

        $conditionData = array_merge(['resource_id' => $resource->id], $data['condition']);

        Condition::create($conditionData);

        $response = $this->json('GET', 'api/condition/000001');
        $response->assertStatus(200);
    }


    /**
     * Test apakah user dapat membuat data kondisi pasien baru
     */
    public function test_users_can_create_new_condition_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getConditionTestData();
        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/condition/create', $data, $headers);
        $response->assertStatus(201);
        // $this->assertDatabaseHas('condition', $data['condition']);
    }

    private function getConditionTestData(): array
    {
        $data = '{
            "condition": {
            "clinical_status": "active",
            "verification_status": "unconfirmed",
            "severity": "24484000",
            "code": "A00",
            "subject": "Patient/100000030009",
            "encounter": "Encounter/3dedcec9-885d-435e-9ac5-58853cb216bb",
            "onset": {
            "onsetDateTime": "2023-10-31T11:43:23+07:00",
            "onsetAge": {
            "value": null,
            "comparator": null,
            "unit": null,
            "system": null,
            "code": null
            },
            "onsetPeriod": {
            "start": null,
            "end": null
            },
            "onsetRange": {
            "low": {
            "value": null,
            "unit": null,
            "system": null,
            "code": null
            },
            "high": {
            "value": null,
            "unit": null,
            "system": null,
            "code": null
            }
            },
            "onsetString": null
            },
            "abatement": {
            "abatementDateTime": "2023-10-31T11:43:23+07:00",
            "abatementAge": {
            "value": null,
            "comparator": null,
            "unit": null,
            "system": null,
            "code": null
            },
            "abatementPeriod": {
            "start": null,
            "end": null
            },
            "abatementRange": {
            "low": {
            "value": null,
            "unit": null,
            "system": null,
            "code": null
            },
            "high": {
            "value": null,
            "unit": null,
            "system": null,
            "code": null
            }
            },
            "abatementString": null
            },
            "recorded_date": "2023-11-01T11.16.01+07:00",
            "recorder": "Practitioner/1000400104",
            "asserter": "Practitioner-1000400104"
            },
            "identifier": [
            {
            "system": "http://sys-ids.kemkes.go.id/condition/10000004",
            "use": "official",
            "value": "5234342"
            }
            ],
            "category": [
            {
            "system": "http://terminology.hl7.org/CodeSystem/condition-category",
            "code": "encounter-diagnosis",
            "display": "Encounter Diagnosis"
            }
            ],
            "body_site": [
            {
            "system": "http://snomed.info/sct",
            "code": "111002",
            "display": "Parathyroid gland"
            }
            ],
            "stage": [
            {
            "stage_data": {
            "summary_system": null,
            "summary_code": null,
            "summary_display": null,
            "type_system": null,
            "type_code": null,
            "type_display": null
            },
            "assessment": [
            {
            "reference": null
            }
            ]
            }
            ],
            "evidence": [
            {
            "system": null,
            "code": null,
            "display": null,
            "detail_reference": null
            }
            ],
            "note": [
            {
            "author": {
            "authorString": "Dokter Bronsig",
            "authorReference": {
            "reference": "Practitioner/1000400104"
            }
            },
            "time": "2023-11-01T11:31:00+07:00",
            "text": "# Catatan<br>## Subbab"
            }
            ]
            }';

        return json_decode($data, true);
    }
}
