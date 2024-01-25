<?php

use App\Http\Controllers\ExternalPurchaseController;
use App\Http\Controllers\IngredientController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('/ingredients', IngredientController::class);
Route::post('/update-quantity', [IngredientController::class, 'updateQuantity']);
Route::get('/purchases-market', [ExternalPurchaseController::class, 'index']);
