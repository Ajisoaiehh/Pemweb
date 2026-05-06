<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Session::get('user');

        if (!$user || $user->ID_PENGGUNA != 1) { // Simple admin check - ID 1 is admin
            return redirect()->route('user.dashboard')->with('error', 'Akses ditolak!');
        }

        return $next($request);
    }
}