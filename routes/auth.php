<?php

use App\Http\Controllers\Auth\AuthenticationController;
 
Route::get('/auth/{provider}/redirect', [AuthenticationController::class, 'redirect']);
Route::get('/auth/{provider}/callback', [AuthenticationController::class, 'callback']);
Route::get('/auth/logout', [AuthenticationController::class, 'destroy']);