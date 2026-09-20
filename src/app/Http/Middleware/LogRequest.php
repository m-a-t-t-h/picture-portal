<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogRequest
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if ($user) {
            $user = $user->email;
        }
        else {
            $user = "ANON";
        }

        Log::debug($request->ip() . " " . str_pad($request->method(), 4) . " " . $user . " " .  $request->fullUrl());

        return $next($request);
    }
}
