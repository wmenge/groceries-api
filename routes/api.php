<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GroceryController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/groceries', [GroceryController::class, 'index']);
Route::get('/groceries/{id}', [GroceryController::class, 'show']);
Route::post('/groceries', [GroceryController::class, 'store']);
Route::put('/groceries/{id}', [GroceryController::class, 'update']); // TODO: Patch?
Route::delete('/groceries/{id}', [GroceryController::class, 'destroy']);