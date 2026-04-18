<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PremiumOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        if (!$request->user()->hasActivePremium()) {
            return response()->json([
                'message' => 'Len prémiový používateľ môže nahrávať prílohy.'
            ], 403);
        }

        return $next($request);
    }
}
