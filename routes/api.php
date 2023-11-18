<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProdutoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('clientes', ClienteController::class)->missing(function (Request $request) {
    return response()->json(['error' => 'Cliente não encontrado'], 404);
});

Route::apiResource('produtos', ProdutoController::class)->missing(function (Request $request) {
    return response()->json(['error' => 'Produto não encontrado'], 404);
});

