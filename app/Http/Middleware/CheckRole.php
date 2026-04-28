<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check() || !in_array(Auth::user()->role, $roles)) {
            
            $userRole = Auth::check() ? Auth::user()->role : '';
            
            if ($userRole === 'kasir') {
                return redirect()->route('sales.create')->with('error', 'Akses ditolak! Kasir hanya boleh mengakses menu transaksi.');
            }
            
            return redirect()->route('dashboard')->with('error', 'Akses ditolak! Anda tidak memiliki izin ke halaman tersebut.');
        }

        return $next($request);
    }
}