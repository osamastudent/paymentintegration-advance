<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            $subscription = $user->subscription('default');
            if ($subscription) {
                $currendate = Carbon::parse(now());
                $expiredate = Carbon::parse($subscription->expire_date);
                if ($currendate->greaterThanOrEqualTo($expiredate)) {
                // if ($expiredate->isPast()) {
                    $subscription->stripe_status = 'expired';
                    $subscription->save();
                    session()->flash('status', 'Your subscription has expired. Please renew your subscription.');
                    Auth::logout();
                    // return redirect()->route('login')->with(['status','Your subscription has expired. Please renew your subscription.']);
                    return redirect()->route('show-plans-loggedin')->with(['status', 'Your subscription has expired. Please renew your subscription.']);
                } else {
                    $subscription->stripe_status = 'active';
                    $subscription->save();
                }
            }
        }

        return $next($request);
    }
}
