<?php

use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
 
Route::get('/auth/{provider}/redirect', [AuthenticationController::class, 'redirect']);
Route::get('/auth/{provider}/callback', [AuthenticationController::class, 'callback']);
Route::get('/auth/logout', [AuthenticatedSessionController::class, 'destroy']);