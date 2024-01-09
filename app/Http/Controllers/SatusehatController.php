<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Fhir\ConsentRequest;
use App\Http\Requests\Fhir\Search\KfaRequest;
use App\Http\Requests\Fhir\Search\LocationSearchRequest;
use App\Http\Requests\Fhir\Search\ObservationSearchRequest;
use App\Http\Requests\Fhir\Search\OrganizationSearchRequest;
use App\Http\Requests\Fhir\Search\PatientSearchRequest;
use App\Http\Requests\Fhir\Search\PractitionerSearchRequest;
use App\Http\Requests\FhirRequest;
use App\Models\Fhir\Resource;
use App\Models\Fhir\Resources\AllergyIntolerance;
use App\Models\Fhir\Resources\ClinicalImpression;
use App\Models\Fhir\Resources\Composition;
use App\Models\Fhir\Resources\Condition;
use App\Models\Fhir\Resources\Encounter;
use App\Models\Fhir\Resources\MedicationRequest;
use App\Models\Fhir\Resources\MedicationStatement;
use App\Models\Fhir\Resources\Observation;
use App\Models\Fhir\Resources\Procedure;
use App\Models\Fhir\Resources\QuestionnaireResponse;
use App\Models\Fhir\Resources\ServiceRequest;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SatusehatController extends Controller
{
    public string $authUrl;
    public string $baseUrl;
    public string $consentUrl;
    public string $kfaV1Url;
    public string $kfaV2Url;
    public string $clientId;
    public string $clientSecret;
    public string $organizationId;

    public function __construct()
    {
        $this->authUrl = config('app.auth_url');
        $this->baseUrl = config('app.base_url');
        $this->consentUrl = config('app.consent_url');
        $this->kfaV1Url = config('app.kfa_v1_url');
        $this->kfaV2Url = config('app.kfa_v2_url');
        $this->clientId = config('app.client_id');
        $this->clientSecret = config('app.client_secret');
        $this->organizationId = config('app.organization_id');
    }

    public function readConsent($patientId)
    {
        $token = $this->getToken();

        $client = new Client();

        $url = $this->consentUrl . '/Consent';

        $response = $client->request('GET', $url, [
            'headers' => ['Authorization' => 'Bearer ' . $token],
            'query' => ['patient_id' => $patientId],
            'verify' => false,
        ]);

        return $response;
    }

    public function updateConsent(ConsentRequest $request)
    {
        $token = $this->getToken();

        $client = new Client();

        $url = $this->consentUrl . '/Consent';

        $data = $request->validated();

        $response = $client->request('POST', $url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
            ],
            'json' => $data,
            'verify' => false,
        ]);

        return $response;
    }

    public function searchKfaProduct(KfaRequest $request) {
        $client = new Client();

        $token = $this->getToken();

        $response = $client->request('GET', config('app.kfa_v2_url') . '/products/all', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
            ],
            'query' => $request->validated(),
            'verify' => false,
        ]);

        return $response;
    }

    public function getToken()
    {
        if (session()->has('token')) {
            if (session()->has('token_created_at')) {
                if (now()->diffInMinutes(session('token_created_at')) < 55) {
                    return session()->get('token');
                }
            }
        }
        session()->forget('token');
        session()->forget('token_created_at');

        $client = new Client();
        $url = $this->authUrl . '/accesstoken?grant_type=client_credentials';
        $headers = ['Content-Type' => 'application/x-www-form-urlencoded',];
        $options = [
            'form_params' => [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
            ],
            'verify' => false,
        ];

        $request = new Request('POST', $url, $headers);

        $response = $client->sendAsync($request, $options)->wait();
        $contents = json_decode($response->getBody()->getContents());
        $token = $contents->access_token;

        session()->put('token', $token);
        session()->put('token_created_at', now());

        return $token;
    }

    public function show($resourceType, $satusehatId)
    {
        $validResourceTypes = array_keys(config('app.available_methods'));

        $resourceType = ctype_upper($resourceType[0]) ? $resourceType : config('app.resource_type_map')[$resourceType];

        if (!in_array($resourceType, $validResourceTypes)) {
            return response()->json([
                'error' => 'Invalid resource type. Keep in mind that resource type is case sensitive.',
                'validResourceTypes' => $validResourceTypes,
            ], 400);
        }

        $method = 'get';
        if (!in_array($method, config('app.available_methods')[$resourceType])) {
            return response()->json([
                'error' => 'Method not allowed for this resource type.',
                'validMethods' => config('app.available_methods')[$resourceType],
            ], 405);
        }

        $token = $this->getToken();

        $client = new Client();


        $url = $this->baseUrl . '/' . $resourceType . '/' . $satusehatId;
        $headers = ['Authorization' => 'Bearer ' . $token,];

        $request = new Request('GET', $url, $headers);

        try {
            $response = $client->sendAsync($request, ['verify' => false])->wait();
            return $response;
        } catch (ClientException $e) {
            Log::error($e->getMessage());
            return response()->json(json_decode(
                $e->getResponse()->getBody()->getContents()
            ), $e->getCode());
        }
    }

    public function store(FhirRequest $fhirRequest, $res_type)
    {
        $resourceType = ctype_upper($res_type[0]) ? $res_type : config('app.resource_type_map')[$res_type];

        $validator = Validator::make($fhirRequest->all(), [
            'resourceType' => ['required', Rule::in(array_keys(config('app.available_methods')))],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $method = 'post';
        if (!in_array($method, config('app.available_methods')[$fhirRequest->input('resourceType')])) {
            return response()->json([
                'error' => 'Method not allowed for this resource type.',
                'validMethods' => config('app.available_methods')[$fhirRequest->input('resourceType')],
            ], 405);
        }

        $token = $this->getToken();

        $client = new Client();

        $resourceType = $fhirRequest->input('resourceType');

        if ($resourceType != $res_type) {
            return response()->json([
                'error' => 'Resource type mismatch, check your request body.',
            ], 400);
        }

        $url = $this->baseUrl . '/' . $resourceType;
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
        ];

        $request = new Request('POST', $url, $headers, json_encode($fhirRequest->all()));

        try {
            $response = $client->sendAsync($request, ['verify' => false])->wait();
            $contents = json_decode($response->getBody()->getContents());
            return response()->json($contents, 201);
        } catch (ClientException $e) {
            return response()->json(json_decode(
                $e->getResponse()->getBody()->getContents()
            ), $e->getCode());
        }
    }

    public function update(FhirRequest $fhirRequest, $res_type, $res_id)
    {
        $res_type = ctype_upper($res_type[0]) ? $res_type : config('app.resource_type_map')[$res_type];

        $validator = Validator::make($fhirRequest->all(), [
            'resourceType' => ['required', Rule::in(array_keys(config('app.available_methods')))],
            'id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $method = 'put';
        if (!in_array($method, config('app.available_methods')[$fhirRequest->input('resourceType')])) {
            return response()->json([
                'error' => 'Method not allowed for this resource type.',
                'validMethods' => config('app.available_methods')[$fhirRequest->input('resourceType')],
            ], 405);
        }

        $token = $this->getToken();

        $client = new Client();

        $resourceType = $fhirRequest->input('resourceType');
        $id = $fhirRequest->input('id');

        if (($resourceType != $res_type) || ($id != $res_id)) {
            return response()->json([
                'error' => 'Resource type or ID mismatch, check your request body.',
            ], 400);
        }

        $url = $this->baseUrl . '/' . $resourceType . '/' . $id;
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        ];

        $request = new Request('PUT', $url, $headers, json_encode($fhirRequest->all()));

        try {
            $response = $client->sendAsync($request, ['verify' => false])->wait();
            $contents = json_decode($response->getBody()->getContents());
            return response()->json($contents, 200);
        } catch (ClientException $e) {
            return response()->json(json_decode(
                $e->getResponse()->getBody()->getContents()
            ), $e->getCode());
        }
    }

    public function updateRekamMedis($patientId)
    {
        $token = $this->getToken();

        $checkResponse = Http::withToken($token)->get($this->baseUrl . '/Patient/' . $patientId);

        if (!$checkResponse->successful()) {
            return response()->json(['error' => 'Data pasien tidak ditemukan'], 404);
        }

        $responses = Http::pool(function (Pool $pool) use ($patientId, $token) {
            foreach (self::PATIENT_RELATED_DATA as $resType => $model) {
                if ($resType == 'Allergyintolerance') {
                    $pool->as($resType)->withToken($token)->get($this->baseUrl . '/' . $resType, ['patient' => $patientId]);
                } else {
                    $pool->as($resType)->withToken($token)->get($this->baseUrl . '/' . $resType, ['subject' => $patientId]);
                }
            }
        });

        foreach (self::PATIENT_RELATED_DATA as $resType => $model) {
            if ($responses[$resType]->successful()) {
                $bundle = json_decode($responses[$resType]->getBody()->getContents(), true);
                $this->bundleHandler($bundle, $resType);
            }
        }

        return response()->json('Data berhasil diperbarui', 200);
    }

    public function bundleHandler($bundle, $resType)
    {
        if (!empty($bundle)) {
            if (!empty($bundle['entry'])) {
                foreach ($bundle['entry'] as $e) {
                    if (!empty($e['resource'])) {
                        if (isset($e['resource']['resourceType'])) {
                            if (strtolower($e['resource']['resourceType']) == strtolower($resType)) {
                                $this->updateOrCreate($resType, $e['resource']['id'], $e['resource']);
                            }
                        }
                    }
                }
            }
        }
    }

    public function checkIfResourceExistsInLocal($resourceType, $resourceId)
    {
        return Resource::where([
            ['res_type', $resourceType],
            ['satusehat_id', $resourceId],
        ])->exists();
    }

    public function updateResourceIfNewer($resourceType, $resourceId, $satusehatResponseBody)
    {
        $resourceType = strtolower($resourceType);

        $resourceUpdatedAt = Resource::where([
            ['res_type', $satusehatResponseBody['resourceType']],
            ['satusehat_id', $satusehatResponseBody['id']],
        ])->first()->updated_at;
        $lastUpdated = Carbon::parse($satusehatResponseBody['meta']['lastUpdated'])->setTimezone(config('app.timezone'));

        if ($lastUpdated->gt($resourceUpdatedAt)) {
            $request = HttpRequest::create(route($resourceType . '.update', ['satusehat_id' => $resourceId]), 'PUT', $satusehatResponseBody);
            $response = app()->handle($request);
            return $response;
        } else {
            return $satusehatResponseBody;
        }
    }

    public function updateOrCreate($resourceType, $resourceId, $resource)
    {
        $resourceType = strtolower($resourceType);

        if ($this->checkIfResourceExistsInLocal($resourceType, $resourceId)) {
            return $this->updateResourceIfNewer($resourceType, $resourceId, $resource);
        } else {
            $request = HttpRequest::create(route($resourceType . '.store'), 'POST', $resource);
            return app()->handle($request);
        }
    }

    public const PATIENT_RELATED_DATA = [
        'Encounter' => Encounter::class,
        'Condition' => Condition::class,
        'Observation' => Observation::class,
        'Procedure' => Procedure::class,
        'MedicationRequest' => MedicationRequest::class,
        'Composition' => Composition::class,
        'AllergyIntolerance' => AllergyIntolerance::class,
        'ClinicalImpression' => ClinicalImpression::class,
        'ServiceRequest' => ServiceRequest::class,
        'MedicationStatement' => MedicationStatement::class,
        'QuestionnaireResponse' => QuestionnaireResponse::class,
    ];

    public function searchPractitioner(PractitionerSearchRequest $request)
    {
        if ($request->query('identifier')) {
            $query = ['identifier' => $request->query('identifier')];
        } elseif ($request->query('name')) {
            $query = [
                'name' => $request->query('name'),
                'gender' => $request->query('gender'),
                'birthdate' => $request->query('birthdate'),
            ];
        } else {
            return response()->json(['error' => 'Either identifier or combination of name, gender, and birthdate must be provided.'], 400);
        }

        $token = $this->getToken();

        $client = new Client();

        $url = $this->baseUrl . '/Practitioner';

        $response = $client->request('GET', $url, [
            'headers' => ['Authorization' => 'Bearer ' . $token],
            'query' => $query,
            'verify' => false,
        ]);

        return $response;
    }

    public function searchOrganization(OrganizationSearchRequest $request)
    {
        if ($request->query('name')) {
            $query = ['name' => $request->query('name')];
        } elseif ($request->query('partof')) {
            $query = ['partof' => $request->query('partof')];
        } else {
            return response()->json(['error' => 'Either name or partof must be provided.'], 400);
        }

        $token = $this->getToken();

        $client = new Client();

        $url = $this->baseUrl . '/Organization';

        $response = $client->request('GET', $url, [
            'headers' => ['Authorization' => 'Bearer ' . $token],
            'query' => $request->validated(),
            'verify' => false,
        ]);

        return $response;
    }

    public function searchLocation(LocationSearchRequest $request)
    {
        if ($request->query('identifier')) {
            $query = ['identifier' => $request->query('identifier')];
        } elseif ($request->query('name')) {
            $query = ['name' => $request->query('name')];
        } elseif ($request->query('organization')) {
            $query = ['organization' => $request->query('organization')];
        } else {
            return response()->json(['error' => 'Either identifier, name, or organization must be provided.'], 400);
        }

        $token = $this->getToken();

        $client = new Client();

        $url = $this->baseUrl . '/Location';

        $response = $client->request('GET', $url, [
            'headers' => ['Authorization' => 'Bearer ' . $token],
            'query' => $request->validated(),
            'verify' => false,
        ]);

        return $response;
    }

    public function searchEncounter(FhirRequest $request)
    {
        if ($request->query('subject')) {
            $query = ['subject' => $request->query('subject')];
        } else {
            return response()->json(['error' => 'subject must be provided.'], 400);
        }

        $token = $this->getToken();

        $client = new Client();

        $url = $this->baseUrl . '/Encounter';

        $response = $client->request('GET', $url, [
            'headers' => ['Authorization' => 'Bearer ' . $token],
            'query' => $query,
            'verify' => false,
        ]);

        return $response;
    }

    public function searchCondition(FhirRequest $request)
    {
        $query = [];

        if ($request->query('subject')) {
            $query['subject'] = $request->query('subject');
        }

        if ($request->query('encounter')) {
            $query['encounter'] = $request->query('encounter');
        }

        if (empty($query)) {
            return response()->json(['error' => 'Either subject and/or encounter must be provided.'], 400);
        }

        $token = $this->getToken();

        $client = new Client();

        $url = $this->baseUrl . '/Condition';

        $response = $client->request('GET', $url, [
            'headers' => ['Authorization' => 'Bearer ' . $token],
            'query' => $query,
            'verify' => false,
        ]);

        return $response;
    }

    public function searchObservation(ObservationSearchRequest $request)
    {
        $query = [];

        if ($request->query('based-on')) {
            $query = [
                'based-on' => $request->query('based-on'),
                'subject' => $request->query('subject'),
            ];
        } else {
            if ($request->query('subject')) {
                $query['subject'] = $request->query('subject');
            }

            if ($request->query('encounter')) {
                $query['encounter'] = $request->query('encounter');
            }
        }

        if (empty($query)) {
            return response()->json(['error' => 'Either a combination of based-on and subject, or encounter and/or subject must be provided.'], 400);
        }

        $token = $this->getToken();

        $client = new Client();

        $url = $this->baseUrl . '/Observation';

        $response = $client->request('GET', $url, [
            'headers' => ['Authorization' => 'Bearer ' . $token],
            'query' => $query,
            'verify' => false,
        ]);

        return $response;
    }

    public function searchComposition(FhirRequest $request)
    {
        $query = [];

        if ($request->query('subject')) {
            $query['subject'] = $request->query('subject');
        }

        if ($request->query('encounter')) {
            $query['encounter'] = $request->query('encounter');
        }

        if (empty($query)) {
            return response()->json(['error' => 'Either subject and/or encounter must be provided.'], 400);
        }

        $token = $this->getToken();

        $client = new Client();

        $url = $this->baseUrl . '/Composition';

        $response = $client->request('GET', $url, [
            'headers' => ['Authorization' => 'Bearer ' . $token],
            'query' => $query,
            'verify' => false,
        ]);

        return $response;
    }

    public function searchProcedure(FhirRequest $request)
    {
        $query = [];

        if ($request->query('subject')) {
            $query['subject'] = $request->query('subject');
        }

        if ($request->query('encounter')) {
            $query['encounter'] = $request->query('encounter');
        }

        if (empty($query)) {
            return response()->json(['error' => 'Either subject and/or encounter must be provided.'], 400);
        }

        $token = $this->getToken();

        $client = new Client();

        $url = $this->baseUrl . '/Procedure';

        $response = $client->request('GET', $url, [
            'headers' => ['Authorization' => 'Bearer ' . $token],
            'query' => $query,
            'verify' => false,
        ]);

        return $response;
    }

    public function searchMedicationRequest(FhirRequest $request)
    {
        $query = [];

        if ($request->query('subject')) {
            $query['subject'] = $request->query('subject');
        }

        if ($request->query('encounter')) {
            $query['encounter'] = $request->query('encounter');
        }

        if (empty($query)) {
            return response()->json(['error' => 'Either subject and/or encounter must be provided.'], 400);
        }

        $token = $this->getToken();

        $client = new Client();

        $url = $this->baseUrl . '/MedicationRequest';

        $response = $client->request('GET', $url, [
            'headers' => ['Authorization' => 'Bearer ' . $token],
            'query' => $query,
            'verify' => false,
        ]);

        return $response;
    }

    public function searchAllergyIntolerance(FhirRequest $request)
    {
        $query = ['patient' => $request->query('patient')];

        if ($request->query('code')) {
            $query['code'] = $request->query('code');
        }

        $token = $this->getToken();

        $client = new Client();

        $url = $this->baseUrl . '/AllergyIntolerance';

        $response = $client->request('GET', $url, [
            'headers' => ['Authorization' => 'Bearer ' . $token],
            'query' => $query,
            'verify' => false,
        ]);

        return $response;
    }

    public function searchClinicalImpression(FhirRequest $request)
    {
        $query = [];

        if ($request->query('subject')) {
            $query['subject'] = $request->query('subject');
        }

        if ($request->query('encounter')) {
            $query['encounter'] = $request->query('encounter');
        }

        if (empty($query)) {
            return response()->json(['error' => 'Either subject and/or encounter must be provided.'], 400);
        }

        $token = $this->getToken();

        $client = new Client();

        $url = $this->baseUrl . '/ClinicalImpression';

        $response = $client->request('GET', $url, [
            'headers' => ['Authorization' => 'Bearer ' . $token],
            'query' => $query,
            'verify' => false,
        ]);

        return $response;
    }

    public function searchQuestionnaireResponse(FhirRequest $request)
    {
        $query = [];

        if ($request->query('subject')) {
            $query['subject'] = $request->query('subject');
        }

        if ($request->query('encounter')) {
            $query['encounter'] = $request->query('encounter');
        }

        if (empty($query)) {
            return response()->json(['error' => 'Either subject and/or encounter must be provided.'], 400);
        }

        $token = $this->getToken();

        $client = new Client();

        $url = $this->baseUrl . '/QuestionnaireResponse';

        $response = $client->request('GET', $url, [
            'headers' => ['Authorization' => 'Bearer ' . $token],
            'query' => $query,
            'verify' => false,
        ]);

        return $response;
    }

    public function searchServiceRequest(FhirRequest $request)
    {
        $query = [];

        if ($request->query('identifier')) {
            $query = ['identifier' => $request->query('identifier')];
        } else {
            if ($request->query('subject')) {
                $query['subject'] = $request->query('subject');
            }

            if ($request->query('encounter')) {
                $query['encounter'] = $request->query('encounter');
            }
        }

        if (empty($query)) {
            return response()->json(['error' => 'Either identifier, or subject and/or encounter must be provided.'], 400);
        }

        $token = $this->getToken();

        $client = new Client();

        $url = $this->baseUrl . '/ServiceRequest';

        $response = $client->request('GET', $url, [
            'headers' => ['Authorization' => 'Bearer ' . $token,],
            'query' => $query,
            'verify' => false,
        ]);

        return $response;
    }

    public function searchPatient(PatientSearchRequest $request)
    {
        $query = [];

        if ($request->query('gender')) {
            $query = [
                'name' => $request->query('name'),
                'birthdate' => $request->query('birthdate'),
                'gender' => $request->query('gender')
            ];
        } elseif ($request->query('identifier')) {
            $query = ['identifier' => $request->query('identifier')];

            if ($request->query('name')) {
                $query['name'] = $request->query('name');
                $query['birthdate'] = $request->query('birthdate');
            }
        } else {
            return response()->json(['error' => 'Either identifier, or combination of: 1) name, birthdate, identifier, or 2) name, birthdate, and gender must be provided.'], 400);
        }

        $token = $this->getToken();

        $client = new Client();

        $url = $this->baseUrl . '/Patient';

        $response = $client->request('GET', $url, [
            'headers' => ['Authorization' => 'Bearer ' . $token,],
            'query' => $query,
            'verify' => false,
        ]);

        return $response;
    }

    public function searchMedicationStatement(FhirRequest $request)
    {
        $query = ['subject' => $request->query('subject')];

        if (empty($query)) {
            return response()->json(['error' => 'Subject must be provided.'], 400);
        }

        $token = $this->getToken();

        $client = new Client();

        $url = $this->baseUrl . '/MedicationStatement';

        $response = $client->request('GET', $url, [
            'headers' => ['Authorization' => 'Bearer ' . $token,],
            'query' => $query,
            'verify' => false,
        ]);

        return $response;
    }
}
