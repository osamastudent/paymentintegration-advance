<?php

namespace App\Http\Controllers;

use auth;
use Carbon\Carbon;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware(['auth','NotSubscribed','Subscribed']);
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    // plans view
    public function plans()
    {
    $plans=Plan::get();
        return view('subscriptions.plans',compact('plans'));
    }
    


    public function checkout(Request $request){
        $id=$request->stripe_id;
         $find= Plan::where('stripe_id',$id)->first();
         $intent = auth()->user()->createSetupIntent();
        return view('subscriptions.checkout',compact('find','intent'));
    }


    public function payment(Request $request){
$plan=Plan::find($request->plan);
$subscription=$request->user()->newSubscription($request->plan,$plan->stripe_id)->create($request->token);
return back();
    }

// cancel plan

public function cancel(Request $request){
$auth=auth()->user();
$sub=Subscription::where('user_id',$auth->id)->orwhere('name','default')->first();
// dd($sub->user_id);
return view('subscriptions.cancel',compact('sub'));
}

// cancel plan

public function cancelPlan(Request $request){
$subscription = $request->user()->subscription('default');
$subscription->cancel();
return back()->with('status','your plan has cancelled.');
}

// resume

public function resume(){
        return view('subscriptions.resume');
    }


public function resumePlan(Request $request){
    $subscription = $request->user()->subscription('default');

    $subscription->resume();
    return back()->with('status','your plan has resumed.');
    }




    public function startTrial(Request $request)
    {
        $planId = $request->plan_id;
        $plan = Plan::findOrFail($planId);
        $user = $request->user();
        $user->createOrGetStripeCustomer();

        // Calculate trial end date
        $trialEndDate = Carbon::now()->addDays($plan->trial_days);

        // Start trial subscription
        try {
            $subscription = $user->newSubscription('default', $plan->stripe_id)
                ->trialUntil($trialEndDate)
                ->create($request->payment_method);

            // Store the interval
            $subscription->update(['interval' => $plan->billing_cycle]);

            return redirect()->route('home')->with('status', 'Trial started successfully.');
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return redirect()->back()->withErrors(['message' => $e->getMessage()]);
        }
    }

    public function createLifetimeSubscription(Request $request)
    {
        $planId = $request->plan_id;
        $plan = Plan::findOrFail($planId);
        $user = $request->user();
        $user->createOrGetStripeCustomer();

        // Start the lifetime subscription
        try {
            $subscription = $user->newSubscription('default', $plan->stripe_id)
                ->create($request->payment_method);

            // Store the interval
            $subscription->update(['interval' => $plan->billing_cycle]);

            return redirect()->route('home')->with('status', 'Lifetime subscription started successfully.');
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return redirect()->back()->withErrors(['message' => $e->getMessage()]);
        }
    }



    // checkout

    // public function checkout(Request $request){
    //     // dd($request->user()->createSetupIntent());
    //     return view('subscriptions.checkout',[
    //         'intent'=>$request->user()->createSetupIntent()
    //     ]);
    // }

    // payment

//     public function payment(Request $request){
//         dd($request->stripe_id);
        
// $plan=Plan::where('slug',$request->plan)->orwhere('slug','monthly')->first();
// // dd("ddd");
// $request->user()->newSubscription('default',$request->stripe_id)->create($request->token);
// return back();
//     }


}
