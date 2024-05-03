<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvestorDashboardController;
use App\Http\Controllers\Auth\RegisteredInvestorController;
use App\Http\Controllers\Auth\AuthenticatedInvestorSessionController;

Route::prefix('investors')->group(function(){

    Route::middleware(['auth:investor'])->group(function(){
        Route::controller(InvestorDashboardController::class)->group(function(){
            Route::get('', "index")->name('investor_dashboard');
        });
    });
    
    Route::middleware('guest:investor')->group(function () {
        Route::get('register', [RegisteredInvestorController::class, 'create'])->name('register');
        Route::post('register', [RegisteredInvestorController::class, 'store']);
        Route::get('login', [AuthenticatedInvestorSessionController::class, 'create'])->name('login');
        Route::post('login', [AuthenticatedInvestorSessionController::class, 'store']);
    }); 
});