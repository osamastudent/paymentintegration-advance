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

    
public function showPlans(){
$basic=ModelsPlan::where('name','Basic')->first();
$standard=ModelsPlan::where('name','Standard')->first();
$premium=ModelsPlan::where('name','premium')->first();
    return view('show-plans',compact('basic','standard','premium'));
}

// checkout

public function checkout($planId){
$plans=ModelsPlan::where('plan_id',$planId)->first();
$user=auth()->user();
return view('checkout', [
    'plans'=>$plans,
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

public function processPlan(Request $request){
    // return $request->all();
    $user=auth()->user();
    $user->createOrGetStripeCustomer();
    $amount = $request->amount;

    $paymentMethod=null;
    $paymentMethod=$request->payment_method;
    if($paymentMethod!=null){
        $paymentMethod=$user->addPaymentMethod($paymentMethod);
    }
    $plan=$request->plan_id;
// dd($paymentMethod->id);
    try{
// $user->newSubscription('default')->create($paymentMethod !=null ? $paymentMethod->id: '' ); 
  $user->newSubscription('default', $plan)->create($paymentMethod->id);
// Subscription created successfully
return back()->with('status', "You are subscribed to this plan.");
}
catch (Exception $ex) {
// Handle subscription creation error
return back()->withErrors([
    'error' => 'Failed to create subscription: ' . $ex->getMessage(),
]);
}

}


// cancel subscription

public function subscriptionShow(){
$subscriptions=auth()->user()->subscriptions;
// dd($subscriptions);
return view('subscription-show',compact('subscriptions'));
}

// cancel plan

public function subscriptionCancel(Request $request) {
    $subscriptionName = $request->subscriptionName;
    
    if ($subscriptionName) {
        auth()->user()->subscription($subscriptionName)->cancel();
        return "Subscription is cancelled.";
    }
}
// resume subscription

public function subscriptionResume(Request $request) {
    $subscriptionName = $request->subscriptionName;
    if ($subscriptionName) {
        auth()->user()->subscription($subscriptionName)->resume();
        return "Subscription is resumed.";
    }
}




    // handle webhook

    // public function handleWebhook(Request $request)
    // {
    //     dd("handleWebhook");
    //     $endpoint_secret = env('STRIPE_ENDPOINT_SECRET');

    //     $payload = @file_get_contents('php://input');
    //     $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
    //     $event = null;

    //     try {
    //         $event = \Stripe\Webhook::constructEvent(
    //             $payload,
    //             $sig_header,
    //             $endpoint_secret
    //         );
    //     } catch (\UnexpectedValueException $e) {
    //         // Invalid payload
    //         http_response_code(400);
    //         exit();
    //     } catch (\Stripe\Exception\SignatureVerificationException $e) {
    //         // Invalid signature
    //         http_response_code(400);
    //         exit();
    //     }
    //     // Handle the event

    //     switch ($event->type) {

    //         case 'customer.subscription.deleted':
    //             $subscription = $event->data->object;
    //             Subscription::where('stripe_id', $subscription->id)->update([
    //                 // 'cancel'=>1,
    //                 // 'canceled_at'=>date('Y-m-d H:i:s'),
    //                 // 'updated_at'=>date('Y-m-d H:i:s'),
    //                 'status' => "inactive",
    //                 'ends_at' => date('Y-m-d H:i:s'),
    //             ]);
    //         case 'customer.subscription.paused':
    //             $subscription = $event->data->object;
    //         case 'customer.subscription.resumed':
    //             $subscription = $event->data->object;
    //         case 'customer.created':
    //             $customer = $event->data->object;
    //         case 'customer.deleted':
    //             $customer = $event->data->object;
    //         case 'customer.updated':
    //             $customer = $event->data->object;
    //         case 'invoice.payment_succeeded':
    //             $invoice = $event->data->object;
    //         case 'subscription_schedule.aborted':
    //             $subscriptionSchedule = $event->data->object;
    //         case 'subscription_schedule.canceled':
    //             $subscriptionSchedule = $event->data->object;
    //         case 'subscription_schedule.completed':
    //             $subscriptionSchedule = $event->data->object;
    //         case 'subscription_schedule.created':
    //             $subscriptionSchedule = $event->data->object;
    //             // ... handle other event types
    //         default:
    //             echo 'Received unknown event type ' . $event->type;
    //     }
    // }


}


// yearly
// price_1PbJuGHiJ6pxhnUHhzG9z4kp

// monthly
// price_1PbJa3HiJ6pxhnUH1KUFblnA