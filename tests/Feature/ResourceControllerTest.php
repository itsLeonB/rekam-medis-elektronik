<?php

namespace Tests\Feature;

use App\Models\Patient;
use App\Models\Resource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ResourceControllerTest extends TestCase
{
    use RefreshDatabase;


    /**
     * Test apakah user dapat membuat data pasien baru
     */
    public function test_users_can_create_new_patient_data()
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
                "deceased": null,
                "marital_status": "M",
                "multiple_birth": false,
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
        $data = json_decode($data, true);
        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/patient', $data, $headers)->assertStatus(201);

        $this->assertDatabaseHas('patient', $data['patient']);
    }


    /**
     * Test apakah user dapat menlihat data pasien
     */
    public function test_users_can_view_patient_data()
    {
        $resource = Resource::create(
            [
                'satusehat_id' => 'P000000',
                'res_type' => 'Patient',
                'res_ver' => 1
            ]
        );

        Patient::create([
            'resource_id' => $resource->id,
            'active' => false,
            'name' => 'Budi Pekerti',
            'gender' => 'male',
            'birth_date' => '2002-11-11',
            'marital_status' => 'M',
            'multiple_birth' => true,
            'language' => 'id'
        ]);

        $response = $this->json('GET', 'api/patient/P000000');

        $response->assertStatus(200);
    }
}
