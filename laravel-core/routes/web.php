<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\PermissionsCheck;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AccessTokenController;
use App\Http\Controllers\ConversationsController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;


Route::middleware(['auth', 'access_token'])->group(function () {
    Route::middleware('permission:consult_dashboard')->controller(DashboardController::class)->group(function(){
        Route::get('', "index")->name('dashboard');
    });
    Route::middleware('permission:consult_conversations')->controller(ConversationsController::class)->group(function(){
        Route::get('conversations', "index")->name('conversations');
        Route::get('conversations/{id}', "conversation")->name('conversations_conversation');
    });
    Route::middleware('permission:consult_orders')->controller(OrdersController::class)->group(function(){
        Route::get('orders', "index")->name('orders');
    });
    Route::middleware('permission:consult_products')->controller(ProductsController::class)->group(function(){
        Route::get('products', "index")->name('products');
    });
    Route::middleware('permission:consult_users')->controller(DashboardController::class)->group(function(){
        Route::get('users', "users")->name('users');
    });
    Route::middleware('permission:consult_settings')->controller(DashboardController::class)->group(function(){
        Route::get('settings', "settings")->name('settings');
    });
    Route::middleware('permission:consult_dashboard')->controller(AccessTokenController::class)->group(function(){
        Route::get('oauth/facebook', 'redirectToFacebook');
        Route::get('oauth/facebook/callback', 'handleFacebookCallback')->withoutMiddleware('access_token');
        Route::get('oauth/facebook/logout', 'logout');
    });
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});