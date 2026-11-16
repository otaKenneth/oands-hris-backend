<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('auth/google', [AuthController::class, 'googleSignIn'])->middleware('throttle:10,1');