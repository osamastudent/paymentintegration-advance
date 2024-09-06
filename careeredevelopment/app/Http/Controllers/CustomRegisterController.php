<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\User;
use Stripe\SetupIntent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomRegisterController extends Controller
{
    //
    public function registerForm()
    {
        return view('auth.register-custom');
    }

    public function regitser(Request $request)
    {

        $email=$request->email;
        $planId=$request->plan_id;
// dd($plans);
        $password = $request->password;
        $hashPass = Hash::make($password);
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'status' => 'pending',
            'password' => $hashPass,
        ]);

    // $plans =Plan::where('plan_id', $planId)->first();
 // return view('checkout', [
        //     'plans' => $plans,
        //     'email' => $email,
        //     'intent' => $intent,
        // ]);
        $intent = SetupIntent::create();

        return redirect()->route('payment.card', [
            'planId' => $planId,
            'email' => $email,
        ]);

       
    }


    public function paymentCard($planId, $email){
    $plans =Plan::where('plan_id', $planId)->first();

        $intent = SetupIntent::create();

        return view('checkout', [
            'plans' => $plans,
            'email' => $email,
            'intent' => $intent,
        ]);
    }
}
