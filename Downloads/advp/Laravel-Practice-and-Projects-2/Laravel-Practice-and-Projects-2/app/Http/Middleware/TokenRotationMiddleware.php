<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class TokenRotationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Get the token from the request
        $token = $request->bearerToken();
        
        if ($token) {
            // Find the token in the database
            $personalAccessToken = PersonalAccessToken::findToken($token);
            
            if ($personalAccessToken) {
                // Log token usage for debugging
                \Log::info('Token used for API request', [
                    'token_id' => $personalAccessToken->id,
                    'token_name' => $personalAccessToken->name,
                    'user_id' => $personalAccessToken->tokenable_id,
                    'endpoint' => $request->path(),
                    'method' => $request->method(),
                    'last_used_at' => $personalAccessToken->last_used_at,
                    'created_at' => $personalAccessToken->created_at
                ]);
                
                // Update last used timestamp
                $personalAccessToken->forceFill([
                    'last_used_at' => now(),
                ])->save();
            } else {
                // Token not found - it may have been invalidated
                \Log::warning('Invalid token used', [
                    'token_prefix' => substr($token, 0, 10) . '...',
                    'endpoint' => $request->path(),
                    'method' => $request->method(),
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]);
                
                return response()->json([
                    'message' => 'Token is invalid or has been revoked. Please log in again.',
                    'error' => 'token_invalid',
                    'code' => 'TOKEN_REVOKED'
                ], 401);
            }
        }

        return $next($request);
    }
}
