<?php

namespace App\Http\Middleware;

use App\Http\Controllers\SatusehatTokenController;
use Closure;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Symfony\Component\HttpFoundation\Response;

class VerifyAccessToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $guard=null): Response
    {
        $accessToken = $request->header('Authorization');

        if (empty($accessToken) || !str_starts_with($accessToken, 'Bearer ')) {
            return $this->updateToken($request);
        }

        $accessToken = str_replace('Bearer ', '', $accessToken);

        $sessionToken = session('token');

        if ($accessToken !== $sessionToken) {
            return $this->updateToken($request);
        }

        $tokenTimestamp = session('token_created_at');

        if (now() - $tokenTimestamp > 3300) {
            return $this->updateToken($request);
        }

        return $next($request);
    }

    protected function updateToken(Request $request)
    {
        $tokenController = app()->make(SatusehatTokenController::class);
        $client = app()->make(Client::class);
        $newToken = $tokenController->getAccessToken($client);

        $newRequest = $request->duplicate(['Authorization' => 'Bearer ' . $newToken]);
        $response = app('router')->dispatch($newRequest);

        return $response;
    }
}
