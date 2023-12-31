<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class CertificateLoginMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {        
        // Mendapatkan array dari semua nama session yang ada
        $sessionNames = collect(session()->all())->keys()->toArray();

        // Pemeriksaan apakah ada sesi yang mengandung kata "accepted_"
        if (collect($sessionNames)->contains(fn($name) => str_contains($name, 'accepted_'))) {
            return $next($request);
        } else {
            return redirect()->route('verify.index')->with('error', 'Please verify your Certificate first!');
        }
    }
}
