<?php

namespace App\Http\Controllers;

use Stripe;
use Exception;
use App\Models\Cart;
use App\Models\Plan;
use App\Models\User;
use App\Models\Order;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Laravel\Cashier\Cashier;


class ProductController extends Controller
{

    public function stripe(): View
    {
        // Available Balance: 15844 usd Pending Balance: 648589 usd
        $balance = \Stripe\Balance::retrieve();

        echo "Available Balance: " . $balance->available[0]->amount . " " . $balance->available[0]->currency . "\n";
        echo "Pending Balance: " . $balance->pending[0]->amount . " " . $balance->pending[0]->currency . "\n";
        $plans = Plan::all();
        $showCarts = Cart::where('user_id', auth()->id())->count();

        return view('products', compact('plans', 'showCarts'));
    }

    public function AddToCartAjax(Request $request)
    {


        try {
            $data = [
                'plan_id' => $request->id,
                'name' => $request->name,
                'price' => $request->price,
                'user_id' => auth()->id(),
                'status' => 'pending',
                'quantity' => '1',
            ];
            Cart::create([
                'plan_id' => $request->id,
                'user_id' => auth()->id(),
                'status' => 'pending',
                'price' => $request->price,
                'quantity' => '1',
            ]);
            return response()->json(['status' => "yesssssss bro", 'data' => $data]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    // show cart
    public function showCart()
    {
        $showCarts = Cart::where('user_id', auth()->id())->count();
        $plans = Plan::all();

        if ($showCarts) {
            return view('products', compact('showCarts', 'plans'));
        }
        return back();
    }

    // show cart items

    public function showcartItems()
    {
        $showCarts = Cart::where('user_id', auth()->id())->get();
        return view('showcart', compact('showCarts'));
    }

    // update Cart Items
    public function updateCartItems(Request $request)
    {
        $data = [
            'id' => $request->id,
            'quantity' => $request->quantity,
            'price' => $request->price,
        ];

        $cart = Cart::find($request->id);
        if ($cart) {
            $cart->quantity = $request->quantity;
            $cart->price = $request->price;
            $cart->save();
        }


        return response()->json(['status' => "cart updated successfully", 'data' => $data, 'id' => $cart]);
    }


    public function stripeCheckout(Request $request)
    {
        $user = auth()->user();
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

        $redirectUrl = route('stripe.checkout.success') . '?session_id={CHECKOUT_SESSION_ID}';

        $response = $stripe->checkout->sessions->create([
            'success_url' => $redirectUrl,

            'customer_email' => 'demo@gmail.com',
            // 'customer_email' => $user->email,

            'payment_method_types' => ['link', 'card'],

            'line_items' => [
                [
                    'price_data' => [
                        'product_data' => [
                            'name' => $request->product,
                        ],
                        'unit_amount' => 100 * $request->price,
                        'currency' => 'USD',
                    ],
                    'quantity' => 1
                ],
            ],

            'mode' => 'payment',
            'allow_promotion_codes' => true,
        ]);

        return redirect($response['url']);
    }







    public function stripeCheckouts(Request $request)
    {
        // fisrt approach
        $user = auth()->user();
if(empty($request->price)){
    return back()->with("status","Your cart is empty");
}
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $redirectUrl = route('stripe.checkout.success') . '?session_id={CHECKOUT_SESSION_ID}';
        $line_items = [];

        // Cart table se products ko fetch karna
        $cartProducts = Cart::where('user_id', auth()->id())->get();

        foreach ($cartProducts as $product) {
            $line_items[] = [
                'price_data' => [
                    'product_data' => [
                        'name' => $product->status,
                    ],
                    'unit_amount' => $product->price * 100, // Convert to cents
                    'currency' => 'usd',
                ],
                'quantity' => $product->quantity,
            ];
        }

        $checkoutSession = \Stripe\Checkout\Session::create([
            'line_items' => $line_items,
            'mode' => 'payment',
            'allow_promotion_codes' => true,
            'metadata' => [
                'user_id' => auth()->id()
            ],
            // 'customer_email' => 'osamademo@gmail.com',
            'customer_email' => $user->email,
            // 'success_url' => route('stripe.checkout.success'),
            'success_url' => route('stripe.checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            // 'cancel_url' => route('stripe.checkout.cancel'),
        ]);
        return redirect()->away($checkoutSession->url);



        // second approach


        // $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        // $redirectUrl = route('stripe.checkout.success') . '?session_id={CHECKOUT_SESSION_ID}';
        // $line_items = [];

        // // Cart table se products ko fetch karna
        // $cartProducts = Cart::where('user_id', auth()->id())->get();

        // foreach ($cartProducts as $product) {
        //     $line_items[] = [
        //         'price_data' => [
        //             'product_data' => [
        //                 'name' => $product->status,
        //             ],
        //             'unit_amount' => $product->price * 100, // Convert to cents
        //             'currency' => 'usd',
        //         ],
        //         'quantity' => $product->quantity,
        //     ];
        // }

        // $response = $stripe->checkout->sessions->create([
        //     'success_url' => $redirectUrl,
        //     'customer_email' => 'demo@gmail.com',
        //     'payment_method_types' => ['card'],
        //     'line_items' => $line_items,
        //     'mode' => 'payment',
        //     'allow_promotion_codes' => true,
        // ]);

        // return redirect($response->url);




    }




    public function success(Request $request)
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

        try {
            $response = $stripe->checkout->sessions->retrieve($request->session_id);

            // dd($response->payment_intent);

            // Store the session ID in the session
            session()->put('session_id', $request->session_id);

            $cartItems = Cart::where("user_id", auth()->id())->get();
            foreach ($cartItems  as $cart) {
                $order =  Order::create([
                    'plan_id' => $cart->plan_id,
                    'user_id' => $cart->user_id,
                    'status' => "paid",
                    'amount' => $cart->price,
                    'payment_id' => $response->payment_intent,
                ]);
            }
            if ($order) {
            Cart::where('user_id',auth()->id())->delete();
            }


            return redirect()->route('stripe.index')
                ->with('success', 'Payment successful.');
        } catch (\Exception $e) {
            return redirect()->route('stripe.index')
                ->with('error', 'Payment failed: ' . $e->getMessage());
        }
    }
    public function cancel(Request $request)
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

        try {
            // Retrieve the Checkout Session
            $checkoutSession = $stripe->checkout->sessions->retrieve($request->session_id);

            // Handle payment intent refund if available
            if ($checkoutSession->payment_intent) {
                $paymentIntent = $stripe->paymentIntents->retrieve($checkoutSession->payment_intent);
                if ($paymentIntent->status === 'succeeded') {
                    // Create a refund for the payment
                    $stripe->refunds->create([
                        'payment_intent' => $checkoutSession->payment_intent,
                        'amount' => $paymentIntent->amount_received, // Optionally specify the amount to refund
                    ]);
                }
            }

            // Handle subscription cancellation if applicable
            if ($checkoutSession->subscription) {
                $stripe->subscriptions->cancel($checkoutSession->subscription);
            }
session()->forget('session_id');
            return redirect()->route('stripe.index')->with('success', 'Order has been canceled.');
        } catch (\Exception $e) {
            return redirect()->route('stripe.index')->with('error', 'Cancellation failed: ' . $e->getMessage());
        }
    }




    // public function success()
    // {
    //     return "Thanks for you order You have just completed your payment. The seeler will reach out to you as soon as possible";
    // }

    // public function cancel()
    // {
    //     return view('cancel');
    // }





}
