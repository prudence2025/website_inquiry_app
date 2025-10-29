<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminOnly
{
    public function handle(Request $request, Closure $next)
    {
        // Allow only the first user (ID = 1)
        if (auth()->check() && auth()->id() === 1) {
            return $next($request);
        }

        abort(403, 'Unauthorized action.');
    }
}
