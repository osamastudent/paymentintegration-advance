<?php

namespace App\Http\Controllers;

use App\Models\Plan as ModelsPlan;
use Exception;
use Stripe\Charge;
use \Stripe\Stripe;
use Illuminate\Http\Request;
use Laravel\Cashier\Cashier;
use Illuminate\Support\Facades\Auth;
use Stripe\Plan;
use Stripe\Webhook;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    // create plan

    public function createPlan()
    {
        return view('create-plan');
    }
    public function storePlan(Request $request)
    {
        // return $request->all();
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
        $amount = $request->amount * 100;

        try {

            $plan = Plan::create([
                'product' => [
                    'name' => $request->name
                ],
                'amount' => $amount,
                'currency' => $request->currency,
                'interval' => $request->billing_method,
                'interval_count' => $request->interval_count,
            ]);

            //  dd($plan);
            ModelsPlan::create([
                'plan_id' => $plan->id,
                'name' => $request->name,
                'price' => $plan->amount,
                'interval_count' => $plan->interval_count,
                'billing_method' => $plan->interval,
                'currency' => $plan->currency,
            ]);
        } catch (Exception $ex) {
            dd($ex->getMessage());
        }
        return "success";
    }



    public function index()
    {
        $user = auth()->user();
        return view('home', [
            'intent' => $user->createSetupIntent(),
        ]);
    }


    // single charged


    public function singleCharged(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $amount = $request->amount * 100;
        $paymentMethod = $request->payment_method;

        $user = auth()->user();
        $user->createOrGetStripeCustomer();

        $paymentMethod = $user->addPaymentMethod($paymentMethod);

        $user->charge($amount, $paymentMethod->id, [
            'return_url' => route('payment.return')  // Ensure you have a route named 'payment.success'
        ]);

        return "payment successfully.";
    }

    // show plans


    public function showPlans()
    {
        $basic = ModelsPlan::where('name', 'Basic')->first();
        $standard = ModelsPlan::where('name', 'Standard')->first();
        $premium = ModelsPlan::where('name', 'premium')->first();
        return view('show-plans', compact('basic', 'standard', 'premium'));
    }

    // checkout

    public function checkout($planId)
    {
        $plans = ModelsPlan::where('plan_id', $planId)->first();
        $user = auth()->user();
        return view('checkout', [
            'plans' => $plans,
            'intent' => $user->createSetupIntent(),
        ]);
    }
    // createOrGetStripeCustomer()
    // Agar user ka Stripe account pehle se nahi hai, to naya account banayega.
    // Agar user ka Stripe account pehle se mojood hai, to usay retrieve karke wapis layega.

    // hain aur agar user ka Stripe account pehle se mojood hai (yani uska account pehle se banaya gaya hai), to ye function us user ke existing Stripe account ki details ko retrieve (yaani nikalna) karke aapko wapis de dega.



    // "addPaymentMethod()

    // " ka matlab hai ke Stripe payment gateway mein ek naya payment method (jaise credit card, debit card, ya koi aur payment option) user ke account mein add karna.

    // Jab bhi kisi user ne apne Stripe account mein ek naya payment method jaise credit card ya debit card add kiya hai, ya fir koi dusra payment option jaise bank account details provide ki hain, to developers Stripe ke "addPaymentMethod()" function ka istemal karke us naye payment method ko user ke account mein link kar sakte hain.

    public function processPlan(Request $request)
    {
        // return $request->all();
        $user = auth()->user();
        $user->createOrGetStripeCustomer();
        $amount = $request->amount;

        $paymentMethod = null;
        $paymentMethod = $request->payment_method;
        if ($paymentMethod != null) {
            $paymentMethod = $user->addPaymentMethod($paymentMethod);
        }
        $plan = $request->plan_id;
        // dd($paymentMethod->id);
        try {
            // $user->newSubscription('default')->create($paymentMethod !=null ? $paymentMethod->id: '' ); 
            $user->newSubscription('default', $plan)->create($paymentMethod->id);
            // Subscription created successfully
            return back()->with('status', "You are subscribed to this plan.");
        } catch (Exception $ex) {
            // Handle subscription creation error
            return back()->withErrors([
                'error' => 'Failed to create subscription: ' . $ex->getMessage(),
            ]);
        }
    }


    // cancel subscription

    public function subscriptionShow()
    {
        $subscriptions = auth()->user()->subscriptions;
        // dd($subscriptions);
        return view('subscription-show', compact('subscriptions'));
    }

    // cancel plan

    public function subscriptionCancel(Request $request)
    {
        $subscriptionName = $request->subscriptionName;

        if ($subscriptionName) {
            auth()->user()->subscription($subscriptionName)->cancel();
            return "Subscription is cancelled.";
        }
    }
    // resume subscription

    public function subscriptionResume(Request $request)
    {
        $subscriptionName = $request->subscriptionName;
        if ($subscriptionName) {
            auth()->user()->subscription($subscriptionName)->resume();
            return "Subscription is resumed.";
        }
    }




    // create Plan Day
    public function createPlanDay()
    {
        return view('create-plan-days');
    }

    // Store plan with day
    public function storePlanDay(Request $request)
    {

        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
        $amount = $request->amount * 100; // Convert amount to the smallest currency unit

        try {
            // Create a product if not already created
            $product = \Stripe\Product::create([
                'name' => $request->name,
            ]);

            if ($request->billing_method === 'custom') {
                $intervalCount = $request->custom_duration;
                $interval = 'day'; // Custom interval in days
            } else {
                $intervalCount = $request->interval_count;
                $interval = $request->billing_method; // weekly, monthly, or yearly
            }

            // Create a price object with the calculated amount and interval
            $price = \Stripe\Price::create([
                'unit_amount' => $amount,
                'currency' => $request->currency,
                'recurring' => [
                    'interval' => $interval,
                    'interval_count' => $intervalCount,
                ],
                'product' => $product->id,
            ]);

            // Save the price information in your local database
            ModelsPlan::create([
                'plan_id' => $price->id,
                'name' => $request->name,
                'price' => $price->unit_amount,
                'interval_count' => $price->recurring->interval_count,
                'billing_method' => $price->recurring->interval,
                'currency' => $price->currency,
            ]);
        } catch (\Exception $ex) {
            dd($ex->getMessage());
        }










        return "success";
    }
}


// yearly
// price_1PbJuGHiJ6pxhnUHhzG9z4kp

// monthly
// price_1PbJa3HiJ6pxhnUH1KUFblnA