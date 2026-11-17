<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/refresh', [AuthController::class, 'refresh']);
    Route::resource('business', \App\Http\Controllers\Api\BusinessController::class);
    Route::get('employees/{business}', [\App\Http\Controllers\Api\EmployeesController::class, 'index']);
});

Route::post('/auth/google', [AuthController::class, 'googleSignIn'])->middleware('throttle:10,1');