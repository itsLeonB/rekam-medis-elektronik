<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Resource;
use App\Models\Practitioner;
use App\Models\PractitionerTelecom;
use Illuminate\Support\Facades\Storage;

class PractitionerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $practitioners = Resource::join('resource_content', function ($join) {
            $join->on('resource.res_id', '=', 'resource_content.res_id')
                ->whereColumn('resource.res_version', '=', 'resource_content.res_ver')->where('resource.res_type', '=', 'Practitioner');
        })->get();
        $photo = Storage::disk('public')->files('images');
        foreach ($practitioners as $p) {
            $resContent = json_decode($p->res_text, true);
            $nameData = getName($resContent);
            $identifier = getIdentifier($resContent);
            $gender = getGender($resContent) == null ? 'unknown' : getGender($resContent);
            $birthDate = getBirthDate($resContent) == null ? '1900-01-01' : getBirthDate($resContent);
            $nik = getNik($identifier) == null ? 9999999999999999 : getNik($identifier);
            $ihs = getIhs($identifier) == null ? 'N10000000' : getIhs($identifier);
            $telecom = getTelecom($resContent);

            Practitioner::create(
                [
                    'res_id' => $p->res_id,
                    'nik' => $nik,
                    'ihs_number' => $ihs,
                    'name' => getFullName($nameData),
                    'prefix' => getPrefix($nameData),
                    'suffix' => getSuffix($nameData),
                    'gender' => $gender,
                    'birth_date' => $birthDate,
                    'photo' => $photo[0],
                ]
            );

            if ($telecom != null) {
                foreach ($telecom as $t) {
                    $telecomDetails = getTelecomDetails($t);
                    PractitionerTelecom::create(
                        [
                            'practitioner_id' => $p->res_id,
                            'system' => $telecomDetails['system'],
                            'use' => $telecomDetails['use'],
                            'value' => $telecomDetails['value'],
                        ]
                    );
                }
            }
        }
    }
}
