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
    Route::get('shifts/{business}', [\App\Http\Controllers\Api\ShiftsController::class, 'index']);
    Route::post('shifts/{business}', [\App\Http\Controllers\Api\ShiftsController::class, 'store']);
    Route::get('shifts/{business}/code-only', [\App\Http\Controllers\Api\ShiftsController::class, 'getShiftsForSelection']);
});

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/google', [AuthController::class, 'googleSignIn'])->middleware('throttle:10,1');