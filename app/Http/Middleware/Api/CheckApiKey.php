<?php

namespace App\Http\Middleware\Api;

use Closure;

class CheckApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $apiKey = getMobileAppGeneralSettings("api_key");

        if (empty($apiKey) or $request->header('x-api-key') !== $apiKey) {
            return apiResponse2(0, 'client_identity_error', 'client identification failed.check the api key');
        }

        return $next($request);
    }
}
