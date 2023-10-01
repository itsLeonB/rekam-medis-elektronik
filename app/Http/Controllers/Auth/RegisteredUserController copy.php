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
            'telecom.*.value' => 'required|string',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
