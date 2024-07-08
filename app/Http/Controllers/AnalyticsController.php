<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Fhir\Resources\Patient;
use App\Models\FhirResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    const ENDED_STATUS = ['finished', 'cancelled', 'entered-in-error', 'unknown'];

    public function getActiveEncounters()
    {
        $count = FhirResource::where('resourceType', 'Encounter')
            ->whereNotIn('status', self::ENDED_STATUS)
            ->count();

        return $count;
    }

    public function getThisMonthNewPatients()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $count = FhirResource::where('resourceType', 'Patient')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        return $count;
    }

    public function countPatients()
    {
        $count = FhirResource::where('resourceType', 'Patient')
            ->count();

        return $count;
    }

    public function getEncountersPerMonth()
    {
        $endDate = new \MongoDB\BSON\UTCDateTime(now()->getTimestamp() * 1000);
        $startDate = new \MongoDB\BSON\UTCDateTime(now()->subMonth(13)->getTimestamp() * 1000);

        $encounters = FhirResource::raw(function ($collection) use ($startDate, $endDate) {
            return $collection->aggregate([
                [
                    '$addFields' => [
                        'date' => [
                            '$dateFromString' => [
                                'dateString' => '$period.start'
                            ]
                        ]
                    ]
                ],
                [
                    '$match' => [
                        'resourceType' => 'Encounter',
                        'date' => [
                            '$gte' => $startDate,
                            '$lte' => $endDate
                        ]
                    ]
                ],
                [
                    '$group' => [
                        "_id" => [
                            'date' => [
                                '$dateToString' => [
                                    'format' => '%Y-%m',
                                    'date' => [
                                        '$dateFromParts' => [
                                            'year' => [
                                                '$year' => '$date'
                                            ],
                                            'month' => [
                                                '$month' => '$date'
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            'class' => '$class.code'
                        ],
                        'count' => ['$sum' => 1]
                    ]
                ],
            ]);
        });

        return $encounters;
    }

    public function getPatientAgeGroups()
    {
        $patientCounts = FhirResource::raw(function ($collection) {
            return $collection->aggregate([
                [
                    '$match' => ['resourceType' => 'Patient']
                ],
                [
                    '$set' => [
                        'birthday' => [
                            '$dateFromString' => [
                                'dateString' => '$birthDate',
                                'format' => '%Y-%m-%d'
                            ]
                        ]
                    ]
                ],
                [
                    '$set' => [
                        'age' => [
                            '$subtract' => [
                                ['$subtract' => [['$year' => '$$NOW'], ['$year' => '$birthday']]],
                                ['$cond' => [['$lt' => [['$dayOfYear' => '$birthday'], ['$dayOfYear' => '$$NOW']]], 0, 1]]
                            ]
                        ]
                    ]
                ],
                [
                    '$addFields' => [
                        'age_group' => [
                            '$switch' => [
                                'branches' => [
                                    ['case' => ['$and' => [['$gte' => ['$age', 0]], ['$lt' => ['$age', 5]]]], 'then' => 'balita'],
                                    ['case' => ['$and' => [['$gte' => ['$age', 5]], ['$lt' => ['$age', 11]]]], 'then' => 'kanak'],
                                    ['case' => ['$and' => [['$gte' => ['$age', 11]], ['$lt' => ['$age', 25]]]], 'then' => 'remaja'],
                                    ['case' => ['$and' => [['$gte' => ['$age', 25]], ['$lt' => ['$age', 45]]]], 'then' => 'dewasa'],
                                    ['case' => ['$and' => [['$gte' => ['$age', 45]], ['$lt' => ['$age', 65]]]], 'then' => 'lansia'],
                                    ['case' => ['$gte' => ['$age', 65]], 'then' => 'manula'],
                                ],
                                'default' => 'unknown'
                            ]
                        ]
                    ]
                ],
                [
                    '$group' => [
                        '_id' => '$age_group',
                        'count' => ['$sum' => 1]
                    ]
                ]
            ]);
        });

        return $patientCounts;
    }
}
