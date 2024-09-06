<?php

use App\Http\Middleware\Subscribed;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\PostController;
use App\Http\Middleware\CheckSubscription;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomRegisterController;

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

Auth::routes();

Route::post('/single-charged', [App\Http\Controllers\HomeController::class, 'singleCharged'])->name('single.charge');
Route::get('about',function(){
    return "about";
});

Route::get('/payment-return', function () {
    return 'Payment completed successfully!';
})->name('payment.return');

Route::get('create/plan',[HomeController::class,'createPlan'])->name('create.plan');
Route::post('create/plan',[HomeController::class,'storePlan'])->name('store.plan');
Route::get('create/plan/day',[HomeController::class,'createPlanDay'])->name('create.plan.day');
Route::post('create/plan/day',[HomeController::class,'storePlanDay'])->name('store.plan.day');

// Post
Route::get('posts',[PostController::class,'createPost'])->name('create.post');
Route::post('posts',[PostController::class,'storePost'])->name('store.post');
Route::get('show-post',[PostController::class,'showPost'])->name('show.post');
Route::get('delete.post/{id}',[PostController::class,'deletePost'])->name('delete.post');
Route::get('delete-all-post',[PostController::class,'deleteAllPost'])->name('delete.all.post');
Route::get('edit.post/{id}',[PostController::class,'editPost'])->name('edit.post');
Route::post('update-post/{id}',[PostController::class,'updatePost'])->name('update.post');

// Route::get('show/plans',[HomeController::class,'showPlans'])->name('show.plans')->middleware('Subscribed');
Route::get('process/subscription/{id}',[HomeController::class,'checkout'])->name('checkout');
Route::post('process/subscription',[HomeController::class,'processPlan'])->name('process.plan');

Route::get('subscription/show',[HomeController::class,'subscriptionShow'])->name('subscription.show');
Route::get('subscription/cancel',[HomeController::class,'subscriptionCancel'])->name('subscription.cancel');
Route::get('subscription/resume',[HomeController::class,'subscriptionResume'])->name('subscription.resume');
Route::post('/stripe/webhook',[HomeController::class,'handleWebhook'])->name('handleWebhook');

// Route::get('subscribed/plans',[HomeController::class,'subscribedPlans'])->name('subscribed.plans');

// Route::middleware('Subscribed','check.subscription')->group(function () {
    Route::get('show/plans', [HomeController::class, 'showPlans'])->name('show.plans');
    Route::get('subscribed/plans', [HomeController::class, 'subscribedPlans'])->name('subscribed.plans');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// });


    Route::get('/upgrade-plan/{plan_id}', [HomeController::class, 'upgradePlan'])->name('upgrade.plan');

    Route::get('/start-free-trial', [HomeController::class, 'startFreeTrial'])->name('start.free.trial');
 
//  user logged in

    Route::get('/user-register', [CustomRegisterController::class, 'registerForm'])->name('register.form');
    Route::post('/user-register', [CustomRegisterController::class, 'regitser'])->name('register.user');
    Route::get('/show-plans-loggedin', [HomeController::class, 'showPlansLoggedin'])->name('show-plans-loggedin');
    Route::get('first-register/{id}',[HomeController::class,'firstRegister'])->name('first.register');
    Route::get('/payment/card/{planId}/{email}', [CustomRegisterController::class, 'paymentCard'])->name('payment.card');
    Route::post('process-subscription',[HomeController::class,'processPlanLoggedOut'])->name('processPlanLoggedOut');

    // send email

    route::get('/sendEmail',[MailController::class,'sendEmail'])->name('sendEmail');
route::post('/sendEmail',[MailController::class,'sendEmailToUser'])->name('sendEmailToUser');
route::get('/send-data-view',[MailController::class,'sendDataView'])->name('sendDataView');


route::get('markdowntemplate',[MailController::class,'markdownTemplate']);
route::post('markdowntemplate',[MailController::class,'markdownTemplatesendemail'])->name('markdownTemplatesendemail');

// ajax
route::get('getRecordBody',[MailController::class,'getRecordBody'])->name('getRecordBody');
route::post('updateMessage',[MailController::class,'updateMessage'])->name('update.message');

Route::get('posts-ajax',[PostController::class,'createPostAjax'])->name('create.post.ajax');
Route::post('posts-ajax',[PostController::class,'storePostAjax'])->name('store.post.ajax');
Route::get('posts-ajax',[PostController::class,'showPostAjax'])->name('show.post.ajax');
Route::get('posts-ajax-delete',[PostController::class,'deletePostAjax'])->name('delete.post.ajax');
Route::get('edit.posts-ajax',[PostController::class,'editPostAjax'])->name('edit.post.ajax');
Route::post('update.posts-ajax',[PostController::class,'updatePostAjax'])->name('update.post.ajax');

Route::post('addtocart-ajax',[ProductController::class,'AddToCartAjax'])->name('addtocart.ajax.store');
Route::get('showcart-ajax',[ProductController::class,'showCart'])->name('show.Cart');
Route::get('showcart-items',[ProductController::class,'showcartItems'])->name('show.cart.items');
Route::post('updatecart-items',[ProductController::class,'updateCartItems'])->name('update.cart.items');

// checkout
Route::controller(ProductController::class)->group(function(){
    Route::get('stripe','stripe')->name('stripe.index');
    Route::get('stripe/checkout','stripeCheckout')->name('stripe.checkout');
    Route::get('stripe/stripeCheckouts','stripeCheckouts')->name('stripe.stripeCheckouts');
    Route::get('stripe/checkout/success','success')->name('stripe.checkout.success');
    Route::post('/stripe/checkout/cancel','cancel')->name('stripe.cancel');

});

// invoice.payment_succeeded
// customer.subscription.deleted
// customer.subscription.resumed
// customer.subscription.paused
// subscription_schedule.canceled
// subscription_schedule.aborted

