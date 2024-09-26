<?php

use Illuminate\Support\Facades\Route;
use FmcExample\GoogleLogin\Http\Controllers\AuthController;

Route::post('handleGoogleLogin', [AuthController::class, 'handleGoogleLogin']);

