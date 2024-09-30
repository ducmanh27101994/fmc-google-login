<?php

use Illuminate\Support\Facades\Route;
use FmcExample\GoogleLogin\Http\Controllers\AuthController;

Route::post('handleGoogleLogin', [AuthController::class, 'handleGoogleLogin']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('/example', [AuthController::class, 'example']);
});

