<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Fhir\Resources\Patient;
use App\Models\FhirResource;
use App\Models\Medicine;
use App\Models\MedicineTransaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use MongoDB\BSON\UTCDateTime;

class AnalyticsObatController extends Controller
{
    const ENDED_STATUS = ['finished', 'cancelled', 'entered-in-error', 'unknown'];

    public function getObatMendekatiKadaluarsa(){
        $oneMonthFromNow = Carbon::now()->addMonth();

        //jika list untuk tampilan diperlukan menggunakan get() jika tidak langsung count()
        $result= Medicine::where('expiry_date', '<', $oneMonthFromNow)->get();
        $count = count($result->toArray()); 
        return $count;
    }
    public function getObatStokSedikit(){
        $pipeline = [
            [
                '$match' => [
                    '$expr' => [
                        '$lt' => ['$quantity', '$minimum_quantity']
                    ]
                ]
            ]
        ];

        $result = Medicine::raw(function($collection) use ($pipeline) {
            return $collection->aggregate($pipeline);
        });

        $count = count($result->toArray()); 

        return $count;
    }
    public function getObatFastMoving(){
        //jika list untuk tampilan diperlukan menggunakan get() jika tidak langsung count()
        $result = Medicine::where('is_fast_moving', '=', true)->get();
        $count = count($result->toArray()); 
        return $count;
    }
    public function getObatPenggunaanPalingBanyak(){
        $oneMonthAgo = Carbon::now()->subMonth();
        //jika list untuk tampilan diperlukan menggunakan get() jika tidak langsung count()
        $result = MedicineTransaction::where('created_at', '>=', $oneMonthAgo)
            ->where('quantity', '>=', 50)
            ->get();
        $count = count($result->toArray()); 
        return $count;
    }
    public function getObatTransaksiPerbandinganPerBulan(){
        $now = Carbon::now();

        // Hitung tahun 3 tahun yang lalu dari sekarang
        $subYearsAgo = $now->copy()->subYears(3);
        
        $pipeline = [
            [
                '$match' => [
                    'created_at' => ['$gte' => new UTCDateTime($subYearsAgo)]
                ]
            ],
            [
                '$group' => [
                    '_id' => [
                        'year' => ['$year' => '$created_at'],
                        'month' => ['$month' => '$created_at']
                    ],
                    'total' => ['$sum' => '$quantity']
                ]
            ],
            [
                '$project' => [
                    '_id' => 0,
                    'year' => '$_id.year',
                    'month' => '$_id.month',
                    'total' => '$total'
                ]
            ],
            [
                '$sort' => ['year' => 1, 'month' => 1]
            ]
        ];
        
        $results = MedicineTransaction::raw(function ($collection) use ($pipeline) {
            return $collection->aggregate($pipeline)->toArray();
        });
        
        // Initialize empty data array for each year
        $yearlyData = [];
        for ($year = $now->year - 2; $year <= $now->year; $year++) {
            $yearlyData[$year] = array_fill(0, 12, 0);
        }
        
        // Fill the data with actual transaction values
        foreach ($results as $result) {
            $yearlyData[$result->year][$result->month - 1] = $result->total;
        }
        
        // Convert the data into the required format
        $result = [];
        foreach ($yearlyData as $year => $data) {
            // Filter the data to include only up to the current month of the current year
            if ($year == $now->year) {
                $data = array_slice($data, 0, $now->month);
            }
            $result[] = (object)[
                'name' => (string)$year,
                'data' => $data
            ];
        }
        
        // Return or use the result as needed
        return $result;
    }

    public function getObatPersebaranStok(){
        // $pipeline = [
        //     [
        //         '$group' => [
        //             '_id' => '$uom',
        //             'unit' => ['$sum' => 1],
        //             'count' => ['$sum' => '$quantity']
        //         ]
        //     ]
        // ];
        $pipeline = [
            [
                '$match' => [
                    '$expr' => [
                        '$lt' => ['$quantity', '$minimum_quantity']
                    ]
                ],
            ],
            [
                
                '$group' => [
                    '_id' => '$name',
                    // 'unit' => ['$sum' => 1],
                    'count' => ['$sum' => '$quantity']
                ]                
            ],
            [
                '$sort' => [
                    '_id' => 1 
                ]
            ]
        ];

        $data = Medicine::raw(function($collection) use ($pipeline) {
            return $collection->aggregate($pipeline);
        });
        

        foreach ($data as $entry) {
            $result['label'][] = $entry->_id;
            $result['unit'][] = $entry->unit;
            $result['value'][] = $entry->count;
        }

        // Dummy data
        // $result['label'] = ['Tablet', 'Capsule', 'Syrup'];
        // $result['value'] = [44, 55, 41];
        return $result;
    }


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
