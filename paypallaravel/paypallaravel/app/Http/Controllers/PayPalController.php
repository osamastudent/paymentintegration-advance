<?php

namespace App\Http\Controllers;

use Log;
use Carbon\Carbon;
use App\Models\Cart;
use App\Models\User;
use GuzzleHttp\Client;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Srmklive\PayPal\Services\PayPal;
use PayPal\Exception\PayPalConnectionException;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
class PayPalController extends Controller
{


    /**
     * create transaction.
     *
     * @return \Illuminate\Http\Response
     */
    public function createTransaction()
    {
        return view('paypal.transaction');
    }

    public function showPlans()
    {
        return view('paypal.show-plans');
    }

    /**
     * process transaction.
     *
     * @return \Illuminate\Http\Response
     */
    public function processTransaction(Request $request)
    {
        dd($request->amount);
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();
        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('successTransaction'),
                "cancel_url" => route('cancelTransaction'),
            ],
            "purchase_units" => [
                0 => [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => $request->amount,
                    ]
                ]
            ]
        ]);
        if (isset($response['id']) && $response['id'] != null) {
            // redirect to approve href
            foreach ($response['links'] as $links) {
                if ($links['rel'] == 'approve') {
                    return redirect()->away($links['href']);
                }
            }
            return redirect()
                ->route('createTransaction')
                ->with('error', 'Something went wrong.');
        } else {
            return redirect()
                ->route('createTransaction')
                ->with('error', $response['message'] ?? 'Something went wrong.');
        }
    }



    public function processTransactionPlan(Request $request)
    {

        // dd($request->planId);
        // if (Auth::check()) {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();

        //   dd(auth()->user()->email);
        $response = $provider->createSubscription([
            'plan_id' => $request->planId,
            'application_context' => [
                'brand_name' => env('APP_NAME'),
                'locale' => 'en-US',
                'shipping_preference' => 'NO_SHIPPING',
                'user_action' => 'SUBSCRIBE_NOW',
                'return_url' => route('successTransaction'),
                'cancel_url' => route('cancelTransaction'),
            ],
            'subscriber' => [
                'name' => [
                    'given_name' => "testFirstName",
                    'surname' => "testLastName",
                ],
                'email_address' => "testFirstName@gmail.com",

            ]
        ]);

        // dd($response['id']);

        $user = User::where('email', auth()->user()->email)->first();


        // dd($user);
        $status = $response['status'];
        $status = "PENDING";
        $subscription = Subscription::updateOrCreate(
            [
                'user_id' => auth()->id(),
            ],
            [
                'plan_id' => $request->planId,
                'paypal_id' => $response['id'],
                'status' => $status,
                'start_time' => Carbon::now(),
                'expire_date' => Carbon::now()->addMonth(1),

            ]
        );

        // $date =Carbon::parse($subscription->start_time)->addMonth('1');

        if (isset($response['id']) && $response['id'] != null) {
            // dd($response);
            // redirect to approve href
            foreach ($response['links'] as $links) {
                if ($links['rel'] == 'approve') {
                    return redirect()->away($links['href']);
                }
            }
            return redirect()
                ->route('createTransaction')
                ->with('error', 'Something went wrong.');
        } else {
            return redirect()
                ->route('createTransaction')
                ->with('error', $response['message'] ?? 'Something went wrong.');
        }
    }


    /**
     * success transaction.
     *
     * @return \Illuminate\Http\Response
     */
    // public function successTransaction(Request $request)
    // {
    //     $provider = new PayPalClient;
    //     $provider->setApiCredentials(config('paypal'));
    //     $provider->getAccessToken();
    //     $response = $provider->capturePaymentOrder($request['token']);
    //     if (isset($response['status']) && $response['status'] == 'COMPLETED') {
    //         return redirect()
    //             ->route('createTransaction')
    //             ->with('success', 'Transaction complete.');
    //     } else {
    //         return redirect()
    //             ->route('createTransaction')
    //             ->with('error', $response['message'] ?? 'Something went wrong.');
    //     }
    // }

    public function successTransaction(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();

        // Get the subscription ID from the request
        $subscriptionId = $request['subscription_id'];
        // dd($subscriptionId);
        // Fetch subscription details to confirm status
        $response = $provider->showSubscriptionDetails($subscriptionId);

        if (isset($response['status']) && $response['status'] == 'ACTIVE') {
            // Update subscription status in the database
            $subscription = Subscription::where('paypal_id', $subscriptionId)->first();
            if ($subscription) {
                $subscription->status = 'ACTIVE';
                $subscription->save();
            }

            return redirect()
                ->route('createTransaction')
                ->with('success', 'Subscription activated successfully.');
        } else {
            return redirect()
                ->route('createTransaction')
                ->with('error', $response['message'] ?? 'Something went wrong.');
        }
    }


    /**
     * cancel transaction.
     *
     * @return \Illuminate\Http\Response
     */
    public function cancelTransaction(Request $request)
    {
        return redirect()
            ->route('createTransaction')
            ->with('error', $response['message'] ?? 'You have canceled the transaction.');
    }




    // for tesing purpose
    public function cancelSubscription($subscriptionId)
    {
        // $provider = new PayPalClient;
        // $provider->setApiCredentials(config('paypal'));
        // $provider->getAccessToken();

        // // Fetch subscription details
        // $response = $provider->showSubscriptionDetails($subscriptionId);
        // dd($response);

        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();

        // Fetch current subscription details
        $subscriptionDetails = $provider->showSubscriptionDetails($subscriptionId);

        // Check subscription status
        if (isset($subscriptionDetails['status']) && $subscriptionDetails['status'] === 'CANCELLED') {
            // Update your database accordingly
            $subscription = Subscription::where('paypal_id', $subscriptionId)->first();
            if ($subscription) {
                $subscription->status = 'EXPIRE';
                $subscription->save();
            }

            return redirect()->route('showplans')->with('success', 'Subscription cancelled successfully.');
        } else {
            return redirect()->route('showplans')->with('error', 'Failed to cancel subscription or subscription status is invalid.');
        }
    }


    public function upgradedPlanShow()
    {
       
        return view('paypal.upgrade');
    }


  
    // add to cart
    public function addToCart(Request $request)
    {
        $cartItem = Cart::updateOrCreate(
            [
                'product_id' => $request->product_id,
            ],
            [
                'user_id' => auth()->id(),
                'name' => $request->name,
                'amount' => $request->amount,
                'quantity' => 1, // Default to 1 quantity
                'subtotal' => $request->amount * 1,
            ]
        );

        return redirect()->back()->with('success', 'Product added to cart!');
    }

    // checkout




    public function checkout(Request $request)
    {
        // Fetch the cart data
        $cartItems = Cart::where('user_id', auth()->id())->get();
        $totalAmount = 0;
        $purchaseUnits = [];

        foreach ($cartItems as $item) {
            $purchaseUnits[] = [
                "name" => $item->name,
                "unit_amount" => [
                    "currency_code" => "USD",
                    "value" => $item->amount, // Price of the item
                ],
                "quantity" => $item->quantity, // Quantity of the item
            ];

            // Calculate total amount
            $totalAmount += $item->amount * $item->quantity;
        }

        // Initialize PayPal provider
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();

        // Create the PayPal order
        // $response = $provider->createOrder([
        //     "intent" => "CAPTURE",
        //     "application_context" => [
        //         "return_url" => route('successTransactionCheckOut'),
        //         "cancel_url" => route('cancelTransactionCheckout'),
        //     ],
        //     "purchase_units" => [
        //         [
        //             "amount" => [
        //                 "currency_code" => "USD",
        //                 "value" => $totalAmount, // Total amount of the cart
        //                 "breakdown" => [
        //                     "item_total" => [
        //                         "currency_code" => "USD",
        //                         "value" => $totalAmount, // Total amount of items
        //                     ],
        //                 ],
        //             ],
        //             "items" => $purchaseUnits, // Cart items
        //         ],
        //     ],
        // ]);

        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "brand_name" => "Your Brand Name",
                "locale" => "en-US",
                "return_url" => route('successTransactionCheckOut'),
                "cancel_url" => route('cancelTransactionCheckout'),
            ],
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => $totalAmount,
                        "breakdown" => [
                            "item_total" => [
                                "currency_code" => "USD",
                                "value" => $totalAmount,
                            ]
                        ]
                    ],
                    "shipping" => [
                        "name" => [
                            "full_name" => "osama janab"
                        ],
                        "address" => [
                            "address_line_1" => "123 New Street",
                            "address_line_2" => "karachi 456",
                            "admin_area_2" => "San Francisco",
                            "admin_area_1" => "CA",
                            "postal_code" => "75660",
                            "country_code" => "US"
                        ]
                    ],
                    "items" => $purchaseUnits, // Cart items
                ]
            ]
        ]);


        // Redirect to PayPal approval page if the order was created successfully
        if (isset($response['id']) && $response['id'] != null) {
            foreach ($response['links'] as $link) {
                if ($link['rel'] === 'approve') {
                    return redirect()->away($link['href']);
                }
            }
        }

        // If the order creation fails, redirect with an error
        return redirect()->route('checkout')->with('error', 'Something went wrong with PayPal.');
    }


    // Success callback after payment
    public function successTransactionCheckOut(Request $request)
    {
        // Initialize PayPal provider
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();

        // Get the order ID from the query parameters (PayPal sends this back)
        $orderId = $request->query('token');

        // Capture the order
        $response = $provider->capturePaymentOrder($orderId);

        // Check if the capture was successful
        if (isset($response['status']) && $response['status'] === 'COMPLETED') {
            Cart::where('user_id', auth()->id())->delete();

            // Payment was successful
            return redirect()->route('home')->with('success', 'Payment successful!');
        }

        // If the payment capture fails, redirect with an error
        return redirect()->route('checkout')->with('error', 'Payment capture failed.');
    }


    // Cancel callback after payment
    public function cancelTransactionCheckout(Request $request)
    {
        return redirect()->route('cart')->with('error', 'Payment canceled.');
    }




 



// public function upgradeSubscription(Request $request)
// {
//     // Validate the request
//     $request->validate([
//         'subscriptionId' => 'required|string',
//         'planId' => 'required|string',
//     ]);

//     $subscriptionId = $request->input('subscriptionId');
//     $planId = $request->input('planId');

//     try {
//         // Initialize the PayPal client
//         $paypal = new PayPalClient;
//         $paypal->setApiCredentials(config('paypal'));  // Set API credentials from config file
//         $paypalToken = $paypal->getAccessToken();  // Get PayPal access token

//         // Prepare subscription update data
//         $subscriptionData = [
//             'plan_id' => $planId,
//         ];

//         // Call PayPal API to update the subscription
//         $response = $paypal->updateSubscription($subscriptionId, $subscriptionData);

//         // Check if the subscription was updated successfully
//         if ($response['status'] === 'ACTIVE') {
//             return redirect()->back()->with('success', 'Subscription upgraded successfully!');
//         } else {
//             return redirect()->back()->with('error', 'Failed to upgrade subscription.');
//         }

//     } catch (\Exception $e) {
//         // Handle errors gracefully
//         return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
//     }


// }

protected $provider;

    public function __construct()
    {
        $this->provider = new PayPalClient;
        $this->provider->setApiCredentials(config('paypal'));
    }

    public function upgradeSubscription(Request $request)
    {
        // Validate the input to ensure subscription_id and new_plan_id are provided
        $request->validate([
            'subscription_id' => 'required|string',
            'new_plan_id' => 'required|string',
        ]);
    
        // Get the subscription ID and new plan ID from the request
        $subscriptionId = $request->subscription_id;
        $newPlanId = $request->new_plan_id;
    
        try {
            // Activate the PayPal provider and get the access token
            $this->provider->getAccessToken();
    
            // The minimal required data to update the subscription
            $data = [
                'plan_id' => $newPlanId,
            ];
    
            // Log the request data for debugging purposes
            \Log::info('Upgrade Subscription Request Data:', $data);
    
            // Call the updateSubscription method with the subscription ID and data array
            $response = $this->provider->updateSubscription($subscriptionId, $data);
    
            // Log the response for debugging purposes
            \Log::info('Upgrade Subscription Response:', $response);
    
            // Check for success
            if (isset($response['status']) && $response['status'] === 'ACTIVE') {
                return response()->json([
                    'message' => 'Subscription upgraded successfully!',
                    'data' => $response,
                ]);
            }
    
            return response()->json([
                'error' => 'Failed to upgrade subscription',
                'details' => $response,
            ]);
    
        } catch (\Exception $e) {
            // Log the error for debugging purposes
            \Log::error('Upgrade Subscription Error:', ['error' => $e->getMessage()]);
    
            return response()->json([
                'error' => 'An error occurred',
                'details' => $e->getMessage(),
            ]);
        }
    }
    
    
    
    



}