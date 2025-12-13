<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user=$request->user();
        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }
        
        return $next($request);
    }
}