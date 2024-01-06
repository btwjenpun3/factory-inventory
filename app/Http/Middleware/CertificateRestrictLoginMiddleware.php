<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CertificateRestrictLoginMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $sessionNames = collect(session()->all())->keys()->toArray();
        if (collect($sessionNames)->contains(fn($name) => str_contains($name, 'accepted_'))) {
            return redirect()->route('auth.login.index')->with('restrict', 'You already verification using your Certificate!');
        } else {            
            return $next($request);
        }
    }
}
