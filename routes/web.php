<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DemoRequestController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\TenantController;
use App\Http\Controllers\PricingController;
use Illuminate\Support\Facades\Route;

// Page publique de pricing
Route::get('/', [PricingController::class, 'index'])->name('pricing');

// Admin Auth
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});

// Admin Dashboard (protégé)
Route::prefix('admin')->name('admin.')->middleware('auth:admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Plans
    Route::resource('plans', PlanController::class);
    
    // Tenants
    Route::get('tenants', [TenantController::class, 'index'])->name('tenants.index');
    Route::get('tenants/{tenant}', [TenantController::class, 'show'])->name('tenants.show');
    Route::put('tenants/{tenant}/subscription', [TenantController::class, 'updateSubscription'])->name('tenants.update-subscription');
    Route::post('tenants/{tenant}/suspend', [TenantController::class, 'suspend'])->name('tenants.suspend');
    Route::post('tenants/{tenant}/reactivate', [TenantController::class, 'reactivate'])->name('tenants.reactivate');
    Route::put('tenants/{tenant}/modules', [TenantController::class, 'updateModules'])->name('tenants.update-modules');
    Route::put('tenants/{tenant}/email', [TenantController::class, 'updateEmail'])->name('tenants.update-email');
    Route::get('tenants/{tenant}/verify-email/{token}', [TenantController::class, 'verifyEmail'])->name('tenants.verify-email');
    
    // Subscriptions
    Route::get('subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::get('subscriptions/{subscription}', [SubscriptionController::class, 'show'])->name('subscriptions.show');
    Route::post('subscriptions/{subscription}/cancel', [SubscriptionController::class, 'cancel'])->name('subscriptions.cancel');
    Route::post('subscriptions/{subscription}/renew', [SubscriptionController::class, 'renew'])->name('subscriptions.renew');
    Route::put('subscriptions/{subscription}/change-plan', [SubscriptionController::class, 'changePlan'])->name('subscriptions.change-plan');
    
    // Demo Requests
    Route::get('demo-requests', [DemoRequestController::class, 'index'])->name('demo-requests.index');
    Route::get('demo-requests/{demoRequest}', [DemoRequestController::class, 'show'])->name('demo-requests.show');
    Route::put('demo-requests/{demoRequest}', [DemoRequestController::class, 'update'])->name('demo-requests.update');
    Route::post('demo-requests/{demoRequest}/provision', [DemoRequestController::class, 'provision'])->name('demo-requests.provision');
    Route::delete('demo-requests/{demoRequest}', [DemoRequestController::class, 'destroy'])->name('demo-requests.destroy');
});
