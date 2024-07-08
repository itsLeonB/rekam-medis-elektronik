<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Medicine;
use Illuminate\Support\Str;


class MedicationSeeder extends Seeder
{
    
    public function run()
    {

        // Create a sample medicine price
        for ($i = 1; $i <= 20; $i++) {
            Medicine::create([
                'medicine_code' => 'MED' . $i,
                'name' => 'Medicine ' . $i,
                'expiry_date' => now()->addYear()->addDays(rand(-100, 100)),
                'quantity' => rand(50, 200),
                'package' => 'Box',
                'uom' => 'Tablet',
                'amount_per_package' => 10,
                'manufacturer' => 'Pharma Inc.',
                'is_fast_moving' => rand(0, 1) == 1,
                'ingredients' => [
                    (object)[
                        'ingredient_id' => Str::uuid(),
                        'ingredient_name' => 'Ingredient 1'
                    ],
                    (object)[
                        'ingredient_id' => Str::uuid(),
                        'ingredient_name' => 'Ingredient 2'
                    ],
                ],
                'minimum_quantity' => rand(5, 20),
                'dosage_form' => 'Tablet',
                'prices' => [
                    'base_price' => rand(100, 500),
                    'purchase_price' => rand(150, 600),
                    'treatment_price_1' => rand(100, 1000),
                    'treatment_price_2' => rand(100, 1000),
                    'treatment_price_3' => rand(100, 1000),
                    'treatment_price_4' => rand(100, 1000),
                    'treatment_price_5' => rand(100, 1000),
                    'treatment_price_6' => rand(100, 1000),
                    'treatment_price_7' => rand(100, 1000),
                    'treatment_price_8' => rand(100, 1000),
                    'treatment_price_9' => rand(100, 1000),
                ],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}