<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GroceryController;
use App\Http\Controllers\ShoppingListController;
use App\Http\Controllers\ShoppingListEntryController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/groceries', [GroceryController::class, 'index']);
Route::get('/groceries/{id}', [GroceryController::class, 'show']);
Route::post('/groceries', [GroceryController::class, 'store']);
Route::put('/groceries/{id}', [GroceryController::class, 'update']); // TODO: Patch?
Route::delete('/groceries/{id}', [GroceryController::class, 'destroy']);

Route::get('/shopping-lists', [ShoppingListController::class, 'index']);
Route::get('/shopping-lists/{id}', [ShoppingListController::class, 'show']);
Route::post('/shopping-lists', [ShoppingListController::class, 'store']);
Route::put('/shopping-lists/{id}', [ShoppingListController::class, 'update']); // TODO: Patch?
Route::delete('/shopping-lists/{id}', [ShoppingListController::class, 'destroy']);

Route::get('/shopping-lists/{shoppingListId}/entries', [ShoppingListEntryController::class, 'index']);
Route::get('/shopping-lists/{shoppingListId}/entries/{id}', [ShoppingListEntryController::class, 'show']);
Route::post('/shopping-lists/{shoppingListId}/entries', [ShoppingListEntryController::class, 'store']);
Route::put('/shopping-lists/{shoppingListId}/entries/{id}', [ShoppingListEntryController::class, 'update']); // TODO: Patch?
Route::delete('/shopping-lists/{shoppingListId}/entries/{id}', [ShoppingListEntryController::class, 'destroy']);