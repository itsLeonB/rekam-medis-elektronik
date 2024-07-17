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

    const ENDED_INVOICE_STATUS = [
        'draft',
        'balanced',
        'cancelled',
        'entered-in-error'
    ];

    const ENDED_ACCOUNT_STATUS = [
        'inactive',
        'entered-in-error',
        'on-hold',
        'unknown'
    ];

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

    public function getIssuedInvoice()
    {
        $count = FhirResource::where('resourceType', 'Invoice')
            ->whereNotIn('status', self::ENDED_INVOICE_STATUS)
            ->count();

        return $count;
    }

    public function getActiveAccounts()
    {
        $count = FhirResource::where('resourceType', 'Account')
            ->whereNotIn('status', self::ENDED_ACCOUNT_STATUS)
            ->count();

        return $count;
    }

    public function getActiveClaims()
    {
        $count = FhirResource::where('resourceType', 'Claim')
            ->whereNotIn('status', self::ENDED_ACCOUNT_STATUS)
            ->count();

        return $count;
    }



    public function getInvoicePerMonth()
    {
        $endDate = new \MongoDB\BSON\UTCDateTime(now()->getTimestamp() * 1000);
        $startDate = new \MongoDB\BSON\UTCDateTime(now()->subMonth(13)->getTimestamp() * 1000);

        $invoice = FhirResource::raw(function ($collection) use ($pipeline) {
            return $collection->aggregate($pipeline)->toArray();
        });

        $yearlyData = [];

        $invoice = FhirResource::raw(function ($collection) use ($startDate, $endDate) {
            return $collection->aggregate([
                [
                    '$addFields' => [
                        'date' => [
                            '$dateFromString' => [
                                'dateString' => '$date'
                            ]
                        ]
                    ]
                ],
                [
                    '$match' => [
                        'resourceType' => 'Invoice',
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
                            'class' => '$paymentTerms'
                        ],
                        'count' => ['$sum' => 1]
                    ]
                ],
            ]);
        });

        return $invoice;
    }

    public function getCoverageGroups()
    {
        $coverageCounts = FhirResource::raw(function ($collection) {
            return $collection->aggregate([
                [
                    '$match' => ['resourceType' => 'Coverage']
                ],
                [
                    '$group' => [
                        '_id' => '$type.coding.code',
                        'count' => ['$sum' => 1]
                    ]
                ]
            ]);
        });

        return $coverageCounts;
    }
}
