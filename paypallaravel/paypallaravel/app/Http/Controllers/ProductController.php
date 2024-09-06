<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    //
    public function showProducts(){
    $showProducts=Product::all();
    return view('show-products',compact('showProducts'));
    }
    
    // show carts
    public function showCarts(){
    $showCarts=Cart::all();
    return view('show-carts',compact('showCarts'));
    }
}
