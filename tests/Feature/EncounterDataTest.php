<?php

namespace Tests\Feature;

use App\Models\Encounter;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EncounterDataTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test apakah user dapat menlihat data kunjungan pasien
     */
    public function test_users_can_view_encounter_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getEncounterTestData();

        $resource = Resource::create(
            [
                'satusehat_id' => '000001',
                'res_type' => 'Encounter',
                'res_ver' => 1
            ]
        );

        $encounterData = array_merge(['resource_id' => $resource->id], $data['encounter']);

        Encounter::create($encounterData);

        $response = $this->json('GET', 'api/encounter/000001');
        $response->assertStatus(200);
    }


    /**
     * Test apakah user dapat membuat data kunjungan pasien baru
     */
    public function test_users_can_create_new_encounter_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getEncounterTestData();
        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/encounter/create', $data, $headers);
        $response->assertStatus(201);
        $this->assertDatabaseHas('encounter', $data['encounter']);
    }


    private function getEncounterTestData(): array
    {
        $data = '{
            "encounter": {
            "status": "arrived",
            "class": "AMB",
            "service_type": 117,
            "priority": "A",
            "subject": "Patient/100000030009",
            "episode_of_care": null,
            "based_on": null,
            "period_start": "2023-10-31T10:49:00+07:00",
            "period_end": null,
            "account": null,
            "location": "Location/dc01c797-547a-4e4d-97cd-4ece0630e380",
            "service_provider": "Organization/RSPARA",
            "part_of": null
            },
            "identifier": [
            {
            "system": "http://sys-ids.kemkes.go.id/encounter/RSPARA",
            "use": "official",
            "value": "000001"
            }
            ],
            "status_history": [
            {
            "status": "arrived",
            "period_start": "2023-10-31T10:49:00+07:00",
            "period_end": null
            }
            ],
            "class_history" : [
            {
            "class": "AMB",
            "period_start": "2023-10-31T10:49:00+07:00",
            "period_end": null
            }
            ],
            "participant": [
            {
            "type": "ATND",
            "individual": "Practitioner/1000400104"
            }
            ],
            "reason": [
            {
            "code": 160303001,
            "reference": "Condition/ba0dd351-c30a-4659-994e-0013797b545b"
            }
            ],
            "diagnosis": [
            {
            "condition_reference": null,
            "condition_display": null,
            "use": null,
            "rank": null
            }
            ],
            "hospitalization": [
            {
            "hospitalization_data": {
            "preadmission_identifier_system": null,
            "preadmission_identifier_use": null,
            "preadmission_identifier_value": null,
            "origin": null,
            "admit_source": null,
            "re_admission": null,
            "destination": null,
            "discharge_disposition": null
            },
            "diet": [
            {
            "system": null,
            "code": null,
            "display": null
            }
            ],
            "special_arrangement": [
            {
            "system": null,
            "code": null,
            "display": null
            }
            ]
            }
            ]
            }';

        return json_decode($data, true);
    }
}
