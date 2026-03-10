<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DemoRequestController;

Route::prefix('v1')->group(function () {
    Route::post('/demo-requests', [DemoRequestController::class, 'store']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/demo-requests', [DemoRequestController::class, 'index']);
        Route::get('/demo-requests/{demoRequest}', [DemoRequestController::class, 'show']);
        Route::put('/demo-requests/{demoRequest}', [DemoRequestController::class, 'update']);
    });
});
