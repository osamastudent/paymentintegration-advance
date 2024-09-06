<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Subscribed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            if (auth()->user()->subscribed('default')) {
                if ($request->route()->named('subscribed.plans')) {
                    return $next($request);
                }
                return redirect()->route('subscribed.plans');
            } else {
                if ($request->route()->named('show.plans')) {
                    return $next($request);
                }
                return redirect()->route('show.plans');
            }
        }else{
            return redirect()->route('login');

        }

        return $next($request);
    
        // if ($request->user() && $request->user()->subscribed('default') && ! $request->routeIs('subscribed.plans')) {
        //     return redirect()->route('subscribed.plans');
        // } elseif (! $request->user()->subscribed('default') && ! $request->routeIs('show.plans')) {
        //     return redirect()->route('show.plans');
        // }
    
        // return $next($request);
    }
}
