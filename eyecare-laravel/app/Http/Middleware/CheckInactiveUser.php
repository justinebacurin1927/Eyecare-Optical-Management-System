<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckInactiveUser
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && !auth()->user()->status) {
            auth()->logout();

            return redirect()->route('login')->withErrors(['email' => 'Your account is inactive.']);
        }

        return $next($request);
    }
}
