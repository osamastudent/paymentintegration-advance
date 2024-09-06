<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Cart;
use App\Models\User;
use GuzzleHttp\Client;
use App\Models\Product;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Srmklive\PayPal\Services\PayPal;
use PayPal\Exception\PayPalConnectionException;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PayPalController extends Controller
{

    // Display products for adding to the cart
    public function showProducts()
    {
        $showProducts = Product::all(); // Assuming you have a Product model
        return view('products', compact('showProducts'));
    }

    // Add product to the cart
    public function addToCart(Request $request)
    {
        $cartItem = Cart::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'product_id' => $request->product_id,
            ],
            [
                'name' => $request->name,
                'amount' => $request->amount,
                'quantity' => 1, // Default to 1 quantity
                'subtotal' => $request->amount * 1,
            ]
        );

        return redirect()->back()->with('success', 'Product added to cart!');
    }

    // Display cart items
    public function showCart()
    {
        $cartItems = Cart::where('user_id', auth()->id())->get();
        $total = $cartItems->sum('subtotal');
        return view('show-carts', compact('cartItems', 'total'));
    }



    
    // Handle PayPal checkout
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
        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('successTransactionCheckOut'),
                "cancel_url" => route('cancelTransactionCheckout'),
            ],
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => $totalAmount, // Total amount of the cart
                        "breakdown" => [
                            "item_total" => [
                                "currency_code" => "USD",
                                "value" => $totalAmount, // Total amount of items
                            ],
                        ],
                    ],
                    "items" => $purchaseUnits, // Cart items
                ],
            ],
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
        Cart::where('user_id', auth()->id())->delete();
        return redirect()->route('home')->with('success', 'Payment successful!');
    }

    // Cancel callback after payment
    public function cancelTransactionCheckout(Request $request)
    {
        return redirect()->route('cart')->with('error', 'Payment canceled.');
    }
}
