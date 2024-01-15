<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Fhir\Resource;
use App\Models\Fhir\Resources\Encounter;
use App\Models\Fhir\Resources\Patient;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    const ENDED_STATUS = ['finished', 'cancelled', 'entered-in-error', 'unknown'];

    public function getActiveEncounters()
    {
        $count = Encounter::whereNotIn('status', self::ENDED_STATUS)->count();

        return $count;
    }

    public function getThisMonthNewPatients()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $count = Resource::where('res_type', 'Patient')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        return $count;
    }

    public function countPatients()
    {
        $count = Patient::count();

        return $count;
    }

    public function getEncountersPerMonth()
    {
        $endDate = now();
        $startDate = now()->subMonths(13);

        $encounterCounts = Encounter::selectRaw('DATE_FORMAT(periods.start, "%Y-%m") as month')
            ->selectRaw('codings.code as class')
            ->selectRaw('COUNT(*) as count')
            ->join('periods', 'encounter.id', '=', 'periods.periodable_id')
            ->join('codings', 'encounter.id', '=', 'codings.codeable_id')
            ->where('periods.periodable_type', 'Encounter')
            ->where('codings.codeable_type', 'Encounter')
            ->where('codings.attr_type', 'class')
            ->whereBetween('periods.start', [$startDate, $endDate])
            ->groupBy('month', 'class')
            ->orderBy('month')
            ->get();

        return $encounterCounts;
    }

    public function getPatientAgeGroups()
    {
        $patientCounts = Patient::select(
            DB::raw('CASE
                WHEN DATEDIFF(CURDATE(), birth_date) / 365.25 BETWEEN 0 AND 5 THEN "balita"
                WHEN DATEDIFF(CURDATE(), birth_date) / 365.25 BETWEEN 5 AND 11 THEN "kanak"
                WHEN DATEDIFF(CURDATE(), birth_date) / 365.25 BETWEEN 11 AND 25 THEN "remaja"
                WHEN DATEDIFF(CURDATE(), birth_date) / 365.25 BETWEEN 25 AND 45 THEN "dewasa"
                WHEN DATEDIFF(CURDATE(), birth_date) / 365.25 BETWEEN 45 AND 65 THEN "lansia"
                ELSE "manula"
            END as age_group'),
            DB::raw('count(*) as count')
        )
            ->groupBy('age_group')
            ->get();

        return $patientCounts;
    }
}
