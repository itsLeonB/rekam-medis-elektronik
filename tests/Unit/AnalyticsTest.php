<?php

namespace Tests\Unit;

use App\Models\FhirResource;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AnalyticsTest extends TestCase
{
    use DatabaseTransactions;

    public function test_get_active_encounters()
    {
        $user = User::factory()->create();

        $statuses = ['finished', 'cancelled', 'entered-in-error', 'unknown', 'in-progress', 'onleave', 'planned', 'triaged'];

        foreach ($statuses as $status) {
            FhirResource::factory()->specific('Encounter')->create(['status' => $status]);
        }

        // Instantiate the controller
        $response = $this->actingAs($user)->get(route('analytics.pasien-dirawat'));

        $response->assertSuccessful();
        $this->assertEquals($response->getContent(), 4);
    }

    public function test_get_this_month_new_patients()
    {
        $user = User::factory()->create();

        $patCount = fake()->numberBetween(1, 10);
        $lastMonthCount = fake()->numberBetween(1, 10);

        // Create a new patient resource
        for ($i = 0; $i < $patCount; $i++) {
            FhirResource::factory()->specific('Patient')->create();
        }

        // Create another patient resource from last month
        for ($i = 0; $i < $lastMonthCount; $i++) {
            $pat = FhirResource::factory()->specific('Patient')->create();
            $pat->created_at = now()->subMonth();
            $pat->save();
        }

        // Call the getThisMonthNewPatients method
        $response = $this->actingAs($user)->get(route('analytics.pasien-baru-bulan-ini'));

        // Assert that the response contains the correct count of new patients
        $response->assertSuccessful();
        $this->assertEquals($response->getContent(), $patCount);
    }

    public function test_get_total_patient_count()
    {
        $user = User::factory()->create();

        $patCount = fake()->numberBetween(1, 10);

        // Create a new patient resource
        for ($i = 0; $i < $patCount; $i++) {
            FhirResource::factory()->specific('Patient')->create();
        }

        // Call the countPatients method
        $response = $this->actingAs($user)->get(route('analytics.jumlah-pasien'));

        $response->assertSuccessful();
        $this->assertEquals($response->getContent(), $patCount);
    }

    public function test_get_encounters_per_month()
    {
        $user = User::factory()->create();

        // Create test data
        $encounter1 = FhirResource::factory()->specific('Encounter')->create(
            [
                'period' => [
                    'start' => now()->subMonths(10),
                    'end' => now()->subMonths(10)->addHours(2),
                ],
                'class' => [
                    'code' => 'AMB',
                ]
            ]
        );

        $encounter2 = FhirResource::factory()->specific('Encounter')->create(
            [
                'period' => [
                    'start' => now()->subMonths(8),
                    'end' => now()->subMonths(8)->addHours(2),
                ],
                'class' => [
                    'code' => 'AMB',
                ]
            ]
        );

        $encounter3 = FhirResource::factory()->specific('Encounter')->create(
            [
                'period' => [
                    'start' => now()->subMonths(6),
                    'end' => now()->subMonths(6)->addHours(2),
                ],
                'class' => [
                    'code' => 'EMER',
                ]
            ]
        );

        $encounter4 = FhirResource::factory()->specific('Encounter')->create(
            [
                'period' => [
                    'start' => now()->subMonths(4),
                    'end' => now()->subMonths(4)->addHours(2),
                ],
                'class' => [
                    'code' => 'EMER',
                ]
            ]
        );

        $encounter5 = FhirResource::factory()->specific('Encounter')->create(
            [
                'period' => [
                    'start' => now()->subMonths(2),
                    'end' => now()->subMonths(2)->addHours(2),
                ],
                'class' => [
                    'code' => 'IMP',
                ]
            ]
        );

        // Call the API endpoint
        $response = $this->actingAs($user)->get(route('analytics.pasien-per-bulan'));

        // Assert the response status code
        $response->assertStatus(200);

        // Assert the response data
        $response->assertJson([
            [
                '_id' => [
                    'month' => data_get($encounter1, 'period.start')->format('Y-m'),
                    'class' => data_get($encounter1, 'class.code'),
                ],
                'count' => 1,
            ],
            [
                '_id' => [
                    'month' => data_get($encounter2, 'period.start')->format('Y-m'),
                    'class' => data_get($encounter2, 'class.code'),
                ],
                'count' => 1,
            ],
            [
                '_id' => [
                    'month' => data_get($encounter3, 'period.start')->format('Y-m'),
                    'class' => data_get($encounter3, 'class.code'),
                ],
                'count' => 1,
            ],
            [
                '_id' => [
                    'month' => data_get($encounter4, 'period.start')->format('Y-m'),
                    'class' => data_get($encounter4, 'class.code'),
                ],
                'count' => 1,
            ],
            [
                '_id' => [
                    'month' => data_get($encounter5, 'period.start')->format('Y-m'),
                    'class' => data_get($encounter5, 'class.code'),
                ],
                'count' => 1,
            ],
        ]);
    }

    public function test_get_patient_age_groups()
    {
        $user = User::factory()->create();

        // Create test data
        $balita = fake()->numberBetween(1, 10);
        $kanak = fake()->numberBetween(1, 10);
        $remaja = fake()->numberBetween(1, 10);
        $dewasa = fake()->numberBetween(1, 10);
        $lansia = fake()->numberBetween(1, 10);
        $manula = fake()->numberBetween(1, 10);

        for ($i = 0; $i < $balita; $i++) {
            FhirResource::factory()->specific('Patient')->create(['birthDate' => now()->subYears(1)]);
        }

        for ($i = 0; $i < $kanak; $i++) {
            FhirResource::factory()->specific('Patient')->create(['birthDate' => now()->subYears(6)]);
        }

        for ($i = 0; $i < $remaja; $i++) {
            FhirResource::factory()->specific('Patient')->create(['birthDate' => now()->subYears(12)]);
        }

        for ($i = 0; $i < $dewasa; $i++) {
            FhirResource::factory()->specific('Patient')->create(['birthDate' => now()->subYears(26)]);
        }

        for ($i = 0; $i < $lansia; $i++) {
            FhirResource::factory()->specific('Patient')->create(['birthDate' => now()->subYears(46)]);
        }

        for ($i = 0; $i < $manula; $i++) {
            FhirResource::factory()->specific('Patient')->create(['birthDate' => now()->subYears(66)]);
        }

        // Make the request to the API endpoint
        $response = $this->actingAs($user)->get(route('analytics.sebaran-usia-pasien'));

        // Assert the response
        $response->assertStatus(200);
        $response->assertJsonFragment(['_id' => 'balita', 'count' => $balita]);
        $response->assertJsonFragment(['_id' => 'kanak', 'count' => $kanak]);
        $response->assertJsonFragment(['_id' => 'remaja', 'count' => $remaja]);
        $response->assertJsonFragment(['_id' => 'dewasa', 'count' => $dewasa]);
        $response->assertJsonFragment(['_id' => 'lansia', 'count' => $lansia]);
        $response->assertJsonFragment(['_id' => 'manula', 'count' => $manula]);
    }
}
