<?php

use App\Http\Middleware\Subscribed;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\NotSubscribed;
use App\Http\Controllers\HomeController;
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

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// Route::get('/plans', [App\Http\Controllers\HomeController::class, 'plans'])->name('plans');
// Route::get('/checkout', [App\Http\Controllers\HomeController::class, 'checkout'])->name('checkout');
// Route::post('/payment', [App\Http\Controllers\HomeController::class, 'payment'])->name('payment');
// Route::get('/cancel', [App\Http\Controllers\HomeController::class, 'cancel'])->name('cancel');
// Route::post('/cancel', [App\Http\Controllers\HomeController::class, 'cancelPlan'])->name('cancel.plan');
// Route::get('/resume', [App\Http\Controllers\HomeController::class, 'resume'])->name('resume');
// Route::post('/resume', [App\Http\Controllers\HomeController::class, 'resumePlan'])->name('resume.plan');
Route::get("/test",function(){
    return "this is test";
})->middleware('NotSubscribed');


Route::middleware(['auth', 'NotSubscribed'])->group(function () {
    Route::get('/plans', [HomeController::class, 'plans'])->name('plans');
    Route::get('/checkout', [HomeController::class, 'checkout'])->name('checkout');
    Route::post('/payment', [HomeController::class, 'payment'])->name('payment');
});

// Routes for subscribed users
Route::middleware(['auth', 'Subscribed'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/cancel', [HomeController::class, 'cancel'])->name('cancel');
    Route::post('/cancel', [HomeController::class, 'cancelPlan'])->name('cancel.plan');
    Route::get('/resume', [HomeController::class, 'resume'])->name('resume');
    Route::post('/resume', [HomeController::class, 'resumePlan'])->name('resume.plan');
});

Route::post('start-trial', [HomeController::class, 'startTrial'])->name('subscriptions.startTrial');