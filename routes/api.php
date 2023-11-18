<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\PedidoController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('clientes', ClienteController::class)->missing(function (Request $request) {
    return response()->json(['error' => 'Cliente não encontrado'], 404);
});

Route::apiResource('produtos', ProdutoController::class)->missing(function (Request $request) {
    return response()->json(['error' => 'Produto não encontrado'], 404);
});

Route::apiResource('pedidos', PedidoController::class)->missing(function (Request $request) {
    return response()->json(['error' => 'Pedido não encontrado'], 404);
});
