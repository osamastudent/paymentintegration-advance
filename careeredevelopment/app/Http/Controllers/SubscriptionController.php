<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    
    public function showSubscription() {
        $plans = $this->retrievePlans();
        $user = Auth::user();
        
        return view('seller.pages.subscribe', [
            'user'=>$user,
            'intent' => $user->createSetupIntent(),
            'plans' => $plans
        ]);
    }
}
