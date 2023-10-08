<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;
use GuzzleHttp\Client;
use Exception;
use Illuminate\Support\Facades\Log;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request, Client $client): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $auth_url = env('auth_url');
        $tokenRequest = $client->request('POST', $auth_url . '/accesstoken?grant_type=client_credentials', [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded'
            ],
            'form_params' => [
                'client_id' => env('client_id'),
                'client_secret' => env('client_secret')
            ],
        ]);

        $responseCode = $tokenRequest->getStatusCode();
        $tokenBody = $tokenRequest->getBody();
        $tokenData = json_decode($tokenBody);

        if ($responseCode == 200 && $tokenData != null) {
            $request->session()->put('token', $tokenData->access_token);
            if ($request->user()->email_verified_at == null) {
                return redirect()->route('verification.notice');
            } else {
                if ($request->user()->password_changed_at == null) {
                    return redirect()->route('profile.edit');
                } else {
                    return redirect()->intended(RouteServiceProvider::HOME);
                }
            }
        } else {
            return back()->with('status', 'Gagal mengotentikasi client pada SATUSEHAT');
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
