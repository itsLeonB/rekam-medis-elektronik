<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Fhir\Resource;
use App\Models\Fhir\Resources\Encounter;
use App\Models\Fhir\Resources\Patient;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function getTodayEncounters()
    {
        $today = now()->toDateString();

        $count = Encounter::whereHas('period', function ($query) use ($today) {
            $query->where('start', '<=', $today)
                ->where('end', '>=', $today);
        })->count();

        return response()->json(['count' => $count]);
    }

    public function getThisMonthNewPatients()
    {
        $firstDayOfMonth = now()->startOfMonth();
        $lastDayOfMonth = now()->endOfMonth();

        // Retrieve the count of resources
        $resourceCount = Resource::where('res_type', 'Patient')
            ->whereBetween('created_at', [$firstDayOfMonth, $lastDayOfMonth])
            ->count();

        return response()->json(['count' => $resourceCount]);
    }

    public function countPatients()
    {
        $count = Patient::count();

        return response()->json(['count' => $count]);
    }

    public function getEncountersPerMonth()
    {
        $endDate = now(); // Current date
        $startDate = now()->subMonths(11); // 12 months ago

        // Retrieve the counts of Encounters grouped by period start and class code
        $encounterCounts = Encounter::selectRaw('DATE_FORMAT(periods.start, "%Y-%m") as month')
            ->selectRaw('codings.code as class')
            ->selectRaw('COUNT(*) as count')
            ->join('periods', function ($join) {
                $join->on('encounter.id', '=', 'periods.periodable_id')
                    ->where('periods.periodable_type', 'Encounter');
            })
            ->join('codings', function ($join) {
                $join->on('encounter.id', '=', 'codings.codeable_id')
                    ->where('codings.codeable_type', 'Encounter')
                    ->where('codings.attr_type', 'class');
            })
            ->whereBetween('periods.start', [$startDate, $endDate])
            ->groupBy('month', 'class')
            ->orderBy('month')
            ->get();

        return response()->json(['data' => $encounterCounts]);
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

        return response()->json(['data' => $patientCounts]);
    }
}
