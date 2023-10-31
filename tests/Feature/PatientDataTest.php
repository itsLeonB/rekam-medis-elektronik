<?php

namespace Tests\Feature;

use App\Models\Patient;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PatientDataTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test apakah user dapat menlihat data pasien
     */
    public function test_users_can_view_patient_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getPatientTestData();

        $resource = Resource::create(
            [
                'satusehat_id' => 'P000000',
                'res_type' => 'Patient',
                'res_ver' => 1
            ]
        );

        $patientData = array_merge(['resource_id' => $resource->id], $data['patient']);

        Patient::create($patientData);

        $response = $this->json('GET', 'api/patient/P000000');
        $response->assertStatus(200);
    }


    /**
     * Test apakah user dapat membuat data pasien baru
     */
    public function test_users_can_create_new_patient_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getPatientTestData();
        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/patient/create', $data, $headers);
        $response->assertStatus(201);
        $this->assertDatabaseHas('patient', $data['patient']);
    }


    private function getPatientTestData(): array
    {
        $data =
            '{
            "patient": {
                "active": true,
                "name": "Budi Pekerti",
                "prefix": "Prof. Dr.",
                "suffix": "S.Kom., M.Kom.",
                "gender": "male",
                "birth_date": "2000-10-10",
                "birth_place": "Surabaya",
                "deceased": {
                    "deceasedBoolean": false
                },
                "marital_status": "M",
                "multiple_birth": {
                    "multipleBirthBoolean": false
                },
                "language": "id"
            },
            "identifier": [
                {
                    "system": "https://fhir.kemkes.go.id/id/nik",
                    "use": "official",
                    "value": "3578020356038885"
                },
                {
                    "system": "https://fhir.kemkes.go.id/id/ihs-number",
                    "use": "official",
                    "value": "P92029102723"
                },
                {
                    "system": "rekam-medis-rsum",
                    "use": "official",
                    "value": "110204"
                }
            ],
            "telecom": [
                {
                    "system": "phone",
                    "use": "mobile",
                    "value": "082393751918"
                },
                {
                    "system": "email",
                    "use": "work",
                    "value": "budi.pekerti@kantor.com"
                }
            ],
            "address": [
                {
                    "use": "home",
                    "line": "Jalan Surabaya Nomor 6 Blok 9",
                    "country": "ID",
                    "postal_code": "60255",
                    "province": 35,
                    "city": 3578,
                    "district": 357802,
                    "village": 3578020001,
                    "rw": 9,
                    "rt": 6
                },
                {
                    "use": "work",
                    "line": "Gedung DPRD Jawa Timur",
                    "country": "ID",
                    "postal_code": "60255",
                    "province": 35,
                    "city": 3578,
                    "district": 357802,
                    "village": 3578020001,
                    "rw": 9,
                    "rt": 6
                }
            ],
            "contact": [
                {
                    "contact_data": {
                        "relationship": "C",
                        "name": "Banyak Pahala",
                        "prefix": null,
                        "suffix": null,
                        "gender": "unknown",
                        "address_use": "home",
                        "address_line": "Gedung DPRD Jawa Timur",
                        "country": "ID",
                        "postal_code": "60255",
                        "province": 35,
                        "city": 3578,
                        "district": 357802,
                        "village": 3578020001,
                        "rw": 9,
                        "rt": 6
                    },
                    "telecom": [
                        {
                            "system": "phone",
                            "use": "mobile",
                            "value": "082393751918"
                        },
                        {
                            "system": "email",
                            "use": "work",
                            "value": "budi.pekerti@kantor.com"
                        }
                    ]
                },
                {
                    "contact_data": {
                        "relationship": "C",
                        "name": "Budi Ajaib",
                        "prefix": null,
                        "suffix": null,
                        "gender": "unknown",
                        "address_use": "home",
                        "address_line": "Gedung DPRD Jawa Timur",
                        "country": "ID",
                        "postal_code": "60255",
                        "province": 35,
                        "city": 3578,
                        "district": 357802,
                        "village": 3578020001,
                        "rw": 9,
                        "rt": 6
                    },
                    "telecom": [
                        {
                            "system": "phone",
                            "use": "mobile",
                            "value": "082393751918"
                        },
                        {
                            "system": "email",
                            "use": "work",
                            "value": "budi.pekerti@kantor.com"
                        }
                    ]
                }
            ],
            "general_practitioner": [
                {
                    "reference": "Practitioner/N10000001"
                },
                {
                    "reference": "Practitioner/N10000001"
                }
            ]
        }';

        return json_decode($data, true);
    }
}
