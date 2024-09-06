<?php

namespace App\Http\Controllers;

use Exception;
use Stripe\Charge;
use Stripe\Stripe;
use Stripe\Webhook;
use App\Models\Plan;
use App\Models\User;
use Stripe\Customer;
use Stripe\SetupIntent;
use Illuminate\Http\Request;
use Laravel\Cashier\Cashier;

use Stripe\Plan as StripePlan;
use Laravel\Cashier\Subscription;
use App\Models\Plan as ModelsPlan;
use Illuminate\Support\Facades\Auth;
use Stripe\Product as StripeProduct;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
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
        // Debug API Key
        $stripeSecret = config('services.stripe.secret');
        // dd($stripeSecret); // Ensure this returns the correct value
        Stripe::setApiKey($stripeSecret); // Ensure this line is executed

        $amount = $request->amount * 100;

        try {
            // Create a product first
            $product = StripeProduct::create([
                'name' => $request->name,
                'type' => 'service',
            ]);

            // Create a plan using the created product
            $plan = StripePlan::create([
                'product' => $product->id,
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
        if ($user) {
            return view('home', [
                'intent' => $user->createSetupIntent(),
            ]);
        } else {
            return redirect()->route('login');
        }
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



    // کینسل شدہ سبسکرپشن: اس کا مطلب ہے کہ صارف نے اپنی سبسکرپشن کو باضابطہ طور پر منسوخ کر دیا ہے۔ اس صورت میں، سبسکرپشن کے تمام فوائد اور رسائی ختم ہو جاتی ہے۔
    // گریس پیریڈ: کینسل ہونے کے بعد بھی کچھ وقت کے لئے صارف کو رسائی فراہم کی جا سکتی ہے، جسے "گریس پیریڈ" کہا جاتا ہے۔ اس دوران صارف کو سروسز تک رسائی حاصل ہوتی رہتی ہے، اور وہ دوبارہ سبسکرپشن کو فعال کر سکتا ہے۔

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
        // dd($subscriptionName);
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
        // return $request->all();
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
        $amount = $request->amount * 100;

        try {

            if ($request->billing_method === 'custom') {
                $intervalCount = $request->custom_duration;
                $interval = 'day'; // Custom interval in days
            } else {
                $intervalCount = $request->interval_count;
                $interval = $request->billing_method; // weekly, monthly, or yearly
            }

            $plan = Plan::create([
                'product' => [
                    'name' => $request->name
                ],
                'amount' => $amount,
                'currency' => $request->currency,
                'interval' => $interval,
                'interval_count' => $intervalCount,
            ]);

            //   dd($plan);
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


    // subscribed plans

    public function subscribedPlans()
    {
        $user = auth()->user();
        // Retrieve the user's subscriptions
        $subscriptions = $user->subscriptions;
        // Retrieve the plans
        $basic = Plan::where('name', 'Basic')->first();
        $standard = Plan::where('name', 'Standard')->first();
        $premium = Plan::where('name', 'Premium')->first();

        return view('', compact('subscriptions', 'basic', 'standard', 'premium'));
    }


    // upgrade Subscription

    public function upgradePlan($plan_id)
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $user = auth()->user();
        $subscription = $user->subscription('default'); // 'default' is the name of your subscription

        try {
            // Ensure that the subscription exists
            if ($subscription) {

                // Swap the subscription with the new plan ID
                $subscription->swap($plan_id);

                // Get the subscription items
                // Get the current plan ID
                //   $currentPlanId = $subscription->stripe_price;
                // dd($currentPlanId);
                // Fetch the current plan details and set it to inactive
                // $currentPlan = ModelsPlan::where('plan_id', $currentPlanId)->first();
                // if ( $currentPlan->status === "active") {
                //     $currentPlan->status = "inactive";
                //     $currentPlan->save();
                // }

                // // Fetch the new plan details and set it to active
                // $newPlan = ModelsPlan::where('plan_id', $plan_id)->first();
                // if ($newPlan) {
                //     $newPlan->status = "active";
                //     $newPlan->save();
                // }
                return redirect()->back()->with('status', 'Subscription upgraded successfully.');
            } else {
                return redirect()->back()->with('status', 'Failed to upgrade subscription: No active subscription found.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('status', 'Failed to upgrade subscription: ' . $e->getMessage());
        }
    }




    // free trial
    public function startFreeTrial(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        $user = auth()->user();
        $plan_id = 'plan_QVHGPsjoalvHSB'; // Replace with your actual plan ID

        try {
            // Create a new subscription with a 10-day trial
            $subscription = $user->newSubscription('default', $plan_id)
                ->trialDays(1)
                ->create();


            // Fetch the new plan details and set it to active

            return redirect()->back()->with('status', 'Free trial started successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('status', 'Failed to start free trial: ' . $e->getMessage());
        }
    }



    // showPlansLoggedin
    public function showPlansLoggedin()
    {

        $basic = ModelsPlan::where('name', 'Basic')->first();
        $standard = ModelsPlan::where('name', 'Standard')->first();
        $premium = ModelsPlan::where('name', 'premium')->first();
        return view('show-plans-loggedin', compact('basic', 'standard', 'premium'));
    }


    public function firstRegister($planId)
    {
        // dd($planId);
        // dd(env('STRIPE_SECRET')); 
        $plans = ModelsPlan::where('plan_id', $planId)->first();
        $intent = SetupIntent::create();
        // Return the view with the plan details and the SetupIntent
        return view('auth.register-custom', [
            'plans' => $plans,
            'intent' => $intent,
        ]);
    }

    public function processPlanLoggedOut(Request $request)
    {

        // yahan mei yser ko loggin bhi kr wa skta hun
        // $user = User::where('email', $request->email)->first();
        // auth()->login($user);
        // $user = auth()->user();


        //  return $request->all();
        //  dd(env('STRIPE_SECRET'));


        $user = User::where('email', $request->email)->first();
        //  dd($user->email);
        $user->createOrGetStripeCustomer();
        $amount = $request->amount;

        $paymentMethod = null;
        $paymentMethod = $request->payment_method;
        if ($paymentMethod != null) {
            $paymentMethod = $user->addPaymentMethod($paymentMethod);
        }
        $plan = $request->plan_id;
        // dd($paymentMethod->id);
        try 
        {
            $subscription = $user->newSubscription('default', $plan)->create($paymentMethod->id);
           
            // Subscription created successfully
            $subscription->start_time = now();
            $subscription->expire_date = now()->addMonth(2); // Adjust based on your subscription duration
            $subscription->save();


            $user->status="paid";
            $user->start_time = now();

            $user->expire_date = now()->addMonth(2); // Adjust based on your subscription duration
            $user->save();


            return redirect()->route('login')->with('status', "You are subscribed to this plan.");
        }
         catch (Exception $ex) {
            // Handle subscription creation error
            return back()->withErrors([
                'error' => 'Failed to create subscription: ' . $ex->getMessage(),
            ]);
        }
    }



}


// yearly
// price_1PbJuGHiJ6pxhnUHhzG9z4kp

// monthly
// price_1PbJa3HiJ6pxhnUH1KUFblnA