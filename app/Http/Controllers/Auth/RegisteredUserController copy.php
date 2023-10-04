<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\Resource;
use App\Models\ResourceContent;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nik' => 'required|integer|digits:16|unique:practitioner,nik',
            'ihsNumber' => 'required|string|size:9|unique:practitioner,ihs_number',
            'name' => 'required|string|max:255',
            'prefix' => 'array',
            'suffix' => 'array',
            'gender' => ['required', 'string', Rule::in(['male', 'female', 'other', 'unknown'])],
            'birthDate' => 'required|date',
            'telecom' => 'required|array',
            'telecom.*' => 'array',
            'telecom.*.system' => ['required', 'string', Rule::in(['phone', 'fax', 'email', 'pager', 'url', 'sms', 'other'])],
            'telecom.*.use' => ['required', 'string', Rule::in(['home', 'work', 'temp', 'old', 'billing'])],
            'address.*.use' => ['required', 'string', Rule::in(['home', 'work', 'temp', 'old', 'billing'])],
            'address.*.line' => 'required|string',
            'address.*.postalCode' => 'required|string',
            'address.*.country' => 'required|string|size:2',
            'address.*.rt' => 'required|integer|max:2',
            'address.*.rw' => 'required|integer|max:2',
            'address.*.province' => 'required|integer|digits:2',
            'address.*.city' => 'required|integer|digits:4',
            'address.*.district' => 'required|integer|digits:6',
            'address.*.village' => 'required|integer|digits:10',
            'qualification.*.code' => 'required|string',
            'qualification.*.codeSystem' => 'required|string',
            'qualification.*.display' => 'required|string',
            'qualification.*.identifier' => 'required|string',
            'qualification.*.issuer' => 'required|string',
            'qualification.*.periodStart' => 'required|date',
            'qualification.*.periodEnd' => 'required|date',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        Validator::make($request->all(), [
            'telecom.*.value' => 'required|string'
        ])->sometimes('telecom.*.value', 'regex:/^[0-9]+$/i', function ($input) {
            return in_array($input['telecom.*.system'], ['phone', 'fax', 'pager', 'sms']);
        })->sometimes('telecom.*.value', 'email|unique:users,email', function ($input) {
            return in_array($input['telecom.*.system'], ['email']);
        });

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $resource = Resource::create([
            'res_type' => 'Practitioner',
            'res_ver' => 1,
            'fhir_ver' => 'R4'
        ]);

        $resourceContent = ResourceContent::create([
            'res_id' => $resource->res_id,
            'res_ver' => 1,
            'res_text' => ''
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
