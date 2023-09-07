<?php

namespace App\Http\Middleware;

use Closure;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddlware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next) : JsonResponse | Response
    {
        if($request->user()->hasRole('admin')){
            return $next($request);

        }

    return response()->json(['message'=>'You cannot access the request'],401);
    }
}
