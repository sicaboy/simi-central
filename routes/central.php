<?php

use App\Http\Controllers\StoreController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['web'])->group(function () {
    // For tenant stripe webhook
    Route::post('/webhook/stripe-connect', [WebhookController::class, 'stripeConnect']);
});

Route::middleware([
    'web',
    'auth',
])->group(function () {
    Route::get('/stores', [StoreController::class, 'getList'])->name('store.list');
    Route::get('/stores/create', [StoreController::class, 'getCreate'])->name('store.create');
    Route::post('/stores', [StoreController::class, 'postCreate'])->name('store.create.post');
    Route::get('/api/business-search', [StoreController::class, 'searchBusiness'])->name('store.business.search');
    Route::get('/api/business-search/{id}', [StoreController::class, 'showBusiness'])->name('store.business.show');
});
