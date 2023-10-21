<?php

namespace Database\Seeders;

use App\Models\Medication;
use App\Models\MedicationIngredient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Resource;

class MedicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $medications = Resource::join('resource_content', function ($join) {
            $join->on('resource.id', '=', 'resource_content.resource_id')
                ->whereColumn('resource.res_version', '=', 'resource_content.res_ver');
        })->where('resource.res_type', 'Medication')
            ->select('resource.*', 'resource_content.res_text')
            ->get();

        foreach ($medications as $m) {
            $resContent = json_decode($m->res_text, true);
            $code = returnCodeableConcept($resContent['code']);
            $ratio = returnRatio($resContent, 'amount');
            $ingredients = returnAttribute($resContent, ['ingredient'], null);

            $med = Medication::create(array_merge(
                [
                    'resource_id' => $m->id,
                    'status' => returnAttribute($resContent, ['status'], null),
                    'manufacturer' => returnAttribute($resContent, ['manufacturer', 'reference'], null),
                    'form' => returnAttribute($resContent, ['form', 'coding', 0, 'code'], null),
                    'batch_lot_number' => returnAttribute($resContent, ['batch', 'lotNumber'], null),
                    'batch_expiration_date' => returnAttribute($resContent, ['batch', 'expirationDate'], null),
                    'type' => returnAttribute($resContent, ['extension', 0, 'valueCodeableConcept', 'coding', 0, 'code'], 'NC')
                ],
                $code,
                $ratio
            ));

            $foreignKey = ['medication_id' => $med->id];

            parseAndCreate(MedicationIngredient::class, $ingredients, 'returnMedicationIngredient', $foreignKey);
        }
    }
}
