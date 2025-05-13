<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user() and auth()->user()->role->name == 'admin') {
            return $next($request);
        } else {
            if (auth()->check()) {
                return redirect('/not-found');
            } else {
                return redirect()->route('login');
            }
        }
    }
}
