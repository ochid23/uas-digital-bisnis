<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsOrganizer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Pastikan user sudah login dan memiliki role 'organizer'
        if (Auth::check() && Auth::user()->role === 'organizer') {
            return $next($request);
        }

        abort(403, 'Akses ditolak. Halaman ini khusus untuk Organizer/Kepanitiaan.');
    }
}