<?php


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OpenAiController;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LanguageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });
// Route::middleware('Subscribed','check.subscription')->group(function () {



Route::get('create-transaction', [PayPalController::class, 'createTransaction'])->name('createTransaction');
Route::post('process-transaction', [PayPalController::class, 'processTransaction'])->name('processTransaction');
Route::get('success-transaction', [PayPalController::class, 'successTransaction'])->name('successTransaction');
Route::get('cancel-transaction', [PayPalController::class, 'cancelTransaction'])->name('cancelTransaction');

Route::get('process-transaction-plan', [PayPalController::class, 'processTransactionPlan'])->name('processTransactionPlan');
Route::get('showplans', [PayPalController::class, 'showPlans'])->name('showplans');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/showproducts', [App\Http\Controllers\ProductController::class, 'showProducts'])->name('show.products');
Route::get('/showCarts', [App\Http\Controllers\ProductController::class, 'showCarts'])->name('show.carts');
// Route::get('process-transaction-checkout', [PayPalController::class, 'checkout'])->name('checkout');
Route::get('cancel-subscription/{subscriptionId}', [PayPalController::class, 'cancelSubscription'])->name('cancelSubscription');
Route::get('upgradedPlanShow', [PayPalController::class, 'upgradedPlanShow'])->name('upgradedPlanShow');
Route::post('upgradeSubscription', [PayPalController::class, 'upgradeSubscription'])->name('upgradedPlan');

Route::get('/products', [PayPalController::class, 'showProducts'])->name('products');
Route::post('/cart/add', [PayPalController::class, 'addToCart'])->name('addToCart');
Route::get('/cart', [PayPalController::class, 'showCart'])->name('cart');

Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [PayPalController::class, 'checkout'])->name('checkout');
    Route::get('/paypal-success', [PayPalController::class, 'successTransactionCheckOut'])->name('successTransactionCheckOut');
    Route::get('/paypal-cancel', [PayPalController::class, 'cancelTransactionCheckout'])->name('cancelTransactionCheckout');
});




Route::get('lang/{lang}', ['as' => 'lang.switch', 'uses' => '\App\Http\Controllers\LanguageController@switchLang']);
Route::get('/languageDemo', 'App\Http\Controllers\HomeController@languageDemo');


// route::get('/{lang?}',function($lang=null){
//     App::setLocale($lang);
//     return view('languageDemo');
//     });


    Route::get('openai', function () {
               return view('openai');
    });
    
    Route::get('/question', [OpenAiController::class, 'index']);

    Route::get('/check',function(){
        return "check check";
    });

    Route::get('/ask', [OpenAiController::class, 'openApiChat']);



//     Route::get('paypal-payment',[PayPalController::class,"payment"])->name('paypal.payment');
// Route::get('paypal-success',[PayPalController::class,"success"])->name('paypal.success');
// Route::get('paypal-cancel',[PayPalController::class,'cancel'])->name('paypal.cancel');