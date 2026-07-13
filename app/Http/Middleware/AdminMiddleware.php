<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }
        
        // Cek apakah user adalah admin (email admin@example.com)
        if (Auth::user()->email !== 'admin@example.com') {
            abort(403, 'Unauthorized access. Admin only.');
        }
        
        return $next($request);
    }
}