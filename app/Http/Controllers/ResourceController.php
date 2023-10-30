<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\PatientResource;
use App\Models\GeneralPractitioner;
use App\Models\Patient;
use App\Models\PatientAddress;
use App\Models\PatientContact;
use App\Models\PatientContactTelecom;
use App\Models\PatientIdentifier;
use App\Models\PatientTelecom;
use App\Models\Resource;
use App\Models\ResourceContent;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ResourceController extends Controller
{
    public function getResource($res_type, $satusehat_id)
    {
        try {
            return response()->json(Resource::where([
                ['satusehat_id', '=', $satusehat_id],
                ['res_type', '=', $res_type]
            ])->firstOrFail()->$res_type->first(), 200);
        } catch (ModelNotFoundException $e) {
            Log::error('Model error: ' . $e->getMessage());
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }
    }

    public function postPatient(Request $request)
    {
        $body = json_decode($request->getContent(), true);

        // Request validation
        if (!isset($body['patient'], $body['identifier'], $body['telecom'], $body['address'], $body['general_practitioner'], $body['contact'])) {
            return response()->json(['error' => 'Data tidak lengkap.'], 400);
        }

        $validator = Validator::make($request->all(), [
            // Patient base data
            'patient.active' => 'required|boolean',
            'patient.name' => 'required|string|max:255',
            'patient.prefix' => 'nullable|string|max:255',
            'patient.suffix' => 'nullable|string|max:255',
            'patient.gender' => ['required', 'string', Rule::in(['male', 'female', 'other', 'unknown'])],
            'patient.birth_date' => 'nullable|date',
            'patient.birth_place' => 'nullable|string|max:255',
            'patient.deceased' => 'nullable|array',
            'patient.marital_status' => ['nullable', 'string', Rule::in(['A', 'D', 'I', 'L', 'M', 'P', 'S', 'T', 'U', 'W'])],
            'patient.multiple_birth' => 'nullable|array',
            'patient.language' => 'nullable|string|max:255',

            // Patient identifier data
            'identifier.*.system' => 'required|string|max:255',
            'identifier.*.use' => ['required', 'string', Rule::in(['usual', 'official', 'temp', 'secondary', 'old'])],
            'identifier.*.value' => 'required|string|max:255',

            // Patient telecom data
            'telecom.*.system' => ['required', 'string', Rule::in(['phone', 'fax', 'email', 'pager', 'url', 'sms', 'other'])],
            'telecom.*.use' => ['required', 'string', Rule::in(['home', 'work', 'temp', 'old', 'mobile'])],
            'telecom.*.value' => 'required|string|max:255',

            // Patient address data
            'address.*.use' => ['required', 'string', Rule::in(['home', 'work', 'temp', 'old', 'billing'])],
            'address.*.line' => 'required|string',
            'address.*.country' => 'required|string|max:255',
            'address.*.postal_code' => 'required|string|max:255',
            'address.*.province' => 'required|integer|gte:0|digits:2',
            'address.*.city' => 'required|integer|gte:0|digits:4',
            'address.*.district' => 'required|integer|gte:0|digits:6',
            'address.*.village' => 'required|integer|gte:0|digits:10',
            'address.*.rt' => 'required|integer|gte:0|max_digits:2',
            'address.*.rw' => 'required|integer|gte:0|max_digits:2',

            // Patient contact data
            'contact.*.contact_data.relationship' => ['required', 'string', Rule::in(['BP', 'CP', 'EP', 'PR', 'E', 'C', 'F', 'I', 'N', 'S', 'U'])],
            'contact.*.contact_data.name' => 'required|string|max:255',
            'contact.*.contact_data.prefix' => 'nullable|string|max:255',
            'contact.*.contact_data.suffix' => 'nullable|string|max:255',
            'contact.*.contact_data.gender' => ['required', 'string', Rule::in(['male', 'female', 'other', 'unknown'])],
            'contact.*.contact_data.address_use' => ['required', 'string', Rule::in(['home', 'work', 'temp', 'old', 'billing'])],
            'contact.*.contact_data.address_line' => 'required|string',
            'contact.*.contact_data.country' => 'required|string|max:255',
            'contact.*.contact_data.postal_code' => 'required|string|max:255',
            'contact.*.contact_data.province' => 'required|integer|gte:0|digits:2',
            'contact.*.contact_data.city' => 'required|integer|gte:0|digits:4',
            'contact.*.contact_data.district' => 'required|integer|gte:0|digits:6',
            'contact.*.contact_data.village' => 'required|integer|gte:0|digits:10',
            'contact.*.contact_data.rt' => 'required|integer|gte:0|max_digits:2',
            'contact.*.contact_data.rw' => 'required|integer|gte:0|max_digits:2',

            // Patient contact telecom data
            'contact.*.telecom.*.system' => ['required', 'string', Rule::in(['phone', 'fax', 'email', 'pager', 'url', 'sms', 'other'])],
            'contact.*.telecom.*.use' => ['required', 'string', Rule::in(['home', 'work', 'temp', 'old', 'mobile'])],
            'contact.*.telecom.*.value' => 'required|string|max:255',

            // General practitioner data
            'general_practitioner.*.reference' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $resource = Resource::create([
                'res_type' => 'Patient',
                'res_ver' => 1,
            ]);

            $resourceKey = ['resource_id' => $resource->id];

            $patient = Patient::create(array_merge($resourceKey, $body['patient']));

            $patientKey = ['patient_id' => $patient->id];

            foreach ($body['identifier'] as $i) {
                PatientIdentifier::create(array_merge($patientKey, $i));
            }

            foreach ($body['telecom'] as $t) {
                PatientTelecom::create(array_merge($patientKey, $t));
            }

            foreach ($body['address'] as $a) {
                PatientAddress::create(array_merge($patientKey, $a));
            }

            foreach ($body['general_practitioner'] as $gp) {
                GeneralPractitioner::create(array_merge($patientKey, $gp));
            }

            foreach ($body['contact'] as $c) {
                $contact = PatientContact::create(array_merge($patientKey, $c['contact_data']));

                $contactKey = ['contact_id' => $contact->id];

                foreach ($c['telecom'] as $ct) {
                    PatientContactTelecom::create(array_merge($contactKey, $ct));
                }
            }

            // $resourceData = new PatientResource($body);
            // $resourceText = json_encode($resourceData);

            // ResourceContent::create([
            //     'res_ver' => 1,
            //     'res_text' => $resourceText,
            // ]);

            DB::commit();

            return response()->json($resource->patient->first(), 201);
        } catch (Exception $e) {
            DB::rollback();
            Log::error('Error: ' . $e->getMessage());
            return response()->json(['error' => 'Server error dalam input data pasien baru.'], 500);
        } catch (QueryException $e) {
            DB::rollback();
            Log::error('Database error: ' . $e->getMessage());
            return response()->json(['error' => 'Database error dalam input data pasien baru.'], 500);
        }
    }
}
