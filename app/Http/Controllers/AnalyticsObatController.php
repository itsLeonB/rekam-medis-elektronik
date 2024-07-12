<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\FhirResource;
use App\Models\Forecast;
use App\Models\Medicine;
use App\Models\MedicineTransaction;
use App\Models\MonthlyStock;
use Carbon\Carbon;
use MongoDB\BSON\UTCDateTime;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

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
                    'unit' => ['$sum' => 1],
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


    public function getForecast(Request $request)
    {
            // URL of the Flask API endpoint
            $flaskApiUrl = 'http://127.0.0.1:5000/process_forecast';

            // Create a new Guzzle HTTP client
            $client = new Client();
    
            try {
                // Send a GET request to the Flask API
                $response = $client->request('GET', $flaskApiUrl);
    
                // Get the response body
                $body = $response->getBody();
                $data = json_decode($body, true);
    
                // Return the response from Flask API
                return response()->json($data);
            } catch (\Exception $e) {
                // Log the error and return a response
                \Log::error("Error processing forecast: " . $e->getMessage());
                return response()->json(['error' => 'Error processing forecast'], 500);
            }
    }

    public function saveMonthlyData(Request $request)
    {
        try {
            $data = $request->input('data'); // Assuming the input JSON is sent as a request payload
    
            $transformedData = [];
            foreach ($data as $item) {
                $year = $item['name'];
                foreach ($item['data'] as $month => $quantity) {
                    $month = $month + 1; // Adjust month to be 1-based
                    // Delete existing records for the same month and year
                    $dt = MonthlyStock::where('year', (int)$year)->where('month', (int)$month)->delete();
                    \Log::info("Transformed Forecast Data: $dt");
    
                    $transformedData[] = [
                        'month' => (int) $month,
                        'year' => (int) $year,
                        'quantity' => (int) $quantity
                    ];
                }
            }
    
            // Save transformed data to MongoDB
            foreach ($transformedData as $data) {
                MonthlyStock::create($data);
            }
    
            return response()->json(['message' => 'Data saved successfully', 'data' => $transformedData]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to save data: ' . $e->getMessage()], 500);
        }
    }

    public function transformForecastData()
    {
        // Retrieve all forecast data from MongoDB
        $forecasts = Forecast::all();

        // Determine the range of years based on the available data
        $years = Forecast::select('year')->orderBy('year', 'asc')->distinct('year')->get();
        \Log::info("Transformed Forecast Data: $years");

        $startYear = min($years);
        $endYear = max($years);

        // Initialize array to hold transformed data
        $transformedData = [];

        // Loop through the range of years to generate data
        foreach ($years as $y) {
            $data = [];
            foreach (range(1, 12) as $month) {
                $found = false;
                foreach ($forecasts as $forecast) {
                    if ($forecast->year == $y && $forecast->month == $month) {
                        $data[] = $forecast->forecast;
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $data[] = 0;
                }
            }
            // Push the year and data into transformedData array
            $transformedData[] = [
                'name' => (string) $y,
                'data' => $data,
            ];
        }

        return response()->json($transformedData);
    }
}