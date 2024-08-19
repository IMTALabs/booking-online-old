<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceJsonValidation
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (($request->isMethod('POST') || $request->isMethod('PUT')) && empty($request->all())) {
            return response()->json(['errors' => ['message' => 'Request body cannot be empty']], 401);
        }

        return $next($request);

    }
}
