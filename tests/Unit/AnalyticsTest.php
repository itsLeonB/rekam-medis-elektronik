<?php

namespace Tests\Unit;

use App\Models\Fhir\Datatypes\Coding;
use App\Models\Fhir\Datatypes\Period;
use App\Models\Fhir\Resource;
use App\Models\Fhir\Resources\Encounter;
use App\Models\Fhir\Resources\Patient;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AnalyticsTest extends TestCase
{
    use DatabaseTransactions;

    public function test_get_today_encounters()
    {
        $encounters = Encounter::factory()->count(4)->create();

        Period::factory()->create([
            'start' => Carbon::today(),
            'end' => Carbon::today()->addHours(2),
            'periodable_id' => $encounters[0]->id,
            'periodable_type' => 'Encounter',
        ]);

        Period::factory()->create([
            'start' => Carbon::today(),
            'end' => Carbon::today()->addHours(4),
            'periodable_id' => $encounters[1]->id,
            'periodable_type' => 'Encounter',
        ]);

        Period::factory()->create([
            'start' => Carbon::yesterday(),
            'end' => Carbon::yesterday()->addHours(2),
            'periodable_id' => $encounters[2]->id,
            'periodable_type' => 'Encounter',
        ]);

        Period::factory()->create([
            'start' => Carbon::tomorrow(),
            'end' => Carbon::tomorrow()->addHours(2),
            'periodable_id' => $encounters[3]->id,
            'periodable_type' => 'Encounter',
        ]);

        // Instantiate the controller
        $response = $this->get(route('analytics.pasien-hari-ini'));

        $response->assertStatus(200);
        $response->assertJson(['count' => 2]);
    }

    public function test_get_this_month_new_patients()
    {
        $patCount = fake()->numberBetween(1, 10);

        // Create a new patient resource
        Resource::factory()->count($patCount)->create([
            'res_type' => 'Patient',
            'created_at' => Carbon::now()->startOfMonth(),
        ]);

        // Create another patient resource from last month
        Resource::factory()->create([
            'res_type' => 'Patient',
            'created_at' => Carbon::now()->subMonth()->startOfMonth(),
        ]);

        // Call the getThisMonthNewPatients method
        $response = $this->get(route('analytics.pasien-baru-bulan-ini'));

        // Assert that the response is successful
        $response->assertStatus(200);

        // Assert that the response contains the correct count of new patients
        $response->assertJson(['count' => $patCount]);
    }

    public function test_get_total_patient_count()
    {
        $patCount = fake()->numberBetween(1, 10);

        // Create a new patient resource
        Patient::factory()->count($patCount)->create();

        // Call the countPatients method
        $response = $this->get(route('analytics.jumlah-pasien'));

        // Assert that the response is successful
        $response->assertStatus(200);

        // Assert that the response contains the correct count of new patients
        $response->assertJson(['count' => $patCount]);
    }

    public function test_get_encounters_per_month()
    {
        // Create test data
        $encounters = Encounter::factory()->count(5)->create();

        Period::factory()->create([
            'start' => now()->subMonths(10),
            'end' => now()->subMonths(10)->addHours(2),
            'periodable_id' => $encounters[0]->id,
            'periodable_type' => 'Encounter',
        ]);

        Period::factory()->create([
            'start' => now()->subMonths(8),
            'end' => now()->subMonths(8)->addHours(2),
            'periodable_id' => $encounters[1]->id,
            'periodable_type' => 'Encounter',
        ]);

        Period::factory()->create([
            'start' => now()->subMonths(6),
            'end' => now()->subMonths(6)->addHours(2),
            'periodable_id' => $encounters[2]->id,
            'periodable_type' => 'Encounter',
        ]);

        Period::factory()->create([
            'start' => now()->subMonths(4),
            'end' => now()->subMonths(4)->addHours(2),
            'periodable_id' => $encounters[3]->id,
            'periodable_type' => 'Encounter',
        ]);

        Period::factory()->create([
            'start' => now()->subMonths(2),
            'end' => now()->subMonths(2)->addHours(2),
            'periodable_id' => $encounters[4]->id,
            'periodable_type' => 'Encounter',
        ]);

        Coding::factory()->create([
            'code' => 'AMB',
            'attr_type' => 'class',
            'codeable_id' => $encounters[0]->id,
            'codeable_type' => 'Encounter',
        ]);

        Coding::factory()->create([
            'code' => 'AMB',
            'attr_type' => 'class',
            'codeable_id' => $encounters[1]->id,
            'codeable_type' => 'Encounter',
        ]);

        Coding::factory()->create([
            'code' => 'EMER',
            'attr_type' => 'class',
            'codeable_id' => $encounters[2]->id,
            'codeable_type' => 'Encounter',
        ]);

        Coding::factory()->create([
            'code' => 'EMER',
            'attr_type' => 'class',
            'codeable_id' => $encounters[3]->id,
            'codeable_type' => 'Encounter',
        ]);

        Coding::factory()->create([
            'code' => 'IMP',
            'attr_type' => 'class',
            'codeable_id' => $encounters[4]->id,
            'codeable_type' => 'Encounter',
        ]);

        // Call the API endpoint
        $response = $this->get(route('analytics.pasien-per-bulan'));

        // Assert the response status code
        $response->assertStatus(200);

        // Assert the response data
        $response->assertJson([
            'data' => [
                [
                    'month' => $encounters[0]->period->start->format('Y-m'),
                    'class' => $encounters[0]->class->code,
                    'count' => 1,
                ],
                [
                    'month' => $encounters[1]->period->start->format('Y-m'),
                    'class' => $encounters[1]->class->code,
                    'count' => 1,
                ],
                [
                    'month' => $encounters[2]->period->start->format('Y-m'),
                    'class' => $encounters[2]->class->code,
                    'count' => 1,
                ],
                [
                    'month' => $encounters[3]->period->start->format('Y-m'),
                    'class' => $encounters[3]->class->code,
                    'count' => 1,
                ],
                [
                    'month' => $encounters[4]->period->start->format('Y-m'),
                    'class' => $encounters[4]->class->code,
                    'count' => 1,
                ],
            ],
        ]);
    }

    public function test_get_patient_age_groups()
    {
        // Create test data
        $balita = fake()->numberBetween(1, 10);
        $kanak = fake()->numberBetween(1, 10);
        $remaja = fake()->numberBetween(1, 10);
        $dewasa = fake()->numberBetween(1, 10);
        $lansia = fake()->numberBetween(1, 10);
        $manula = fake()->numberBetween(1, 10);

        Patient::factory()->count($balita)->create([
            'birth_date' => now()->subYears(1),
        ]);
        Patient::factory()->count($kanak)->create([
            'birth_date' => now()->subYears(6),
        ]);
        Patient::factory()->count($remaja)->create([
            'birth_date' => now()->subYears(12),
        ]);
        Patient::factory()->count($dewasa)->create([
            'birth_date' => now()->subYears(26),
        ]);
        Patient::factory()->count($lansia)->create([
            'birth_date' => now()->subYears(46),
        ]);
        Patient::factory()->count($manula)->create([
            'birth_date' => now()->subYears(66),
        ]);

        // Make the request to the API endpoint
        $response = $this->get(route('analytics.sebaran-usia-pasien'));

        // Assert the response
        $response->assertStatus(200);
        $response->assertJsonFragment(['age_group' => 'balita', 'count' => $balita]);
        $response->assertJsonFragment(['age_group' => 'kanak', 'count' => $kanak]);
        $response->assertJsonFragment(['age_group' => 'remaja', 'count' => $remaja]);
        $response->assertJsonFragment(['age_group' => 'dewasa', 'count' => $dewasa]);
        $response->assertJsonFragment(['age_group' => 'lansia', 'count' => $lansia]);
        $response->assertJsonFragment(['age_group' => 'manula', 'count' => $manula]);
    }
}
