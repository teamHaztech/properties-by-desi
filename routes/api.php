<?php

use App\Http\Controllers\Api\DashboardApiController;
use App\Http\Controllers\Api\LeadApiController;
use App\Http\Controllers\Api\PropertyApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    // Dashboard
    Route::get('/dashboard/stats', [DashboardApiController::class, 'stats']);

    // Leads
    Route::apiResource('leads', LeadApiController::class)->names('api.leads');
    Route::patch('/leads/{lead}/status', [LeadApiController::class, 'updateStatus'])->name('api.leads.update-status');

    // Properties
    Route::apiResource('properties', PropertyApiController::class)->names('api.properties');
});
