<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanyManagerController;

Route::prefix('v1')->group(function () {
    // Public
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/jobs', [JobController::class, 'index']);
    Route::get('/jobs/{job:slug}', [JobController::class, 'show']);

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'me']);

        Route::post('/company/register', [CompanyController::class, 'register']);
        Route::get('/company/profile', [CompanyController::class, 'show']);
        Route::put('/company/profile', [CompanyController::class, 'update']);

        Route::get('/company/managers', [CompanyManagerController::class, 'index']);
        Route::post('/company/managers', [CompanyManagerController::class, 'store']);
        Route::delete('/company/managers/{user}', [CompanyManagerController::class, 'destroy']);

        Route::post('/jobs', [JobController::class, 'store']);
        Route::put('/jobs/{job}', [JobController::class, 'update']);
        Route::delete('/jobs/{job}', [JobController::class, 'destroy']);

        Route::post('/jobs/{job}/apply', [JobController::class, 'apply']);
        Route::post('/applications/{application}/withdraw', [JobController::class, 'withdraw']);
    });
});