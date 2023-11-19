<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\PedidoResource;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NovoPedido;

class PedidoController extends Controller
{

    public function index()
    {
        return Pedido::all();
    }


    public function store(Request $request)
    {

        try {
            DB::beginTransaction();

            $pedido = Pedido::create([
                'cliente_id' => $request->cliente_id,
            ]);

            foreach ($request->produtos as $produto) {
                $pedido->produtos()->attach($produto['produto_id'], ['quantidade' => $produto['quantidade']]);
            }

            DB::commit();

            $cliente = $pedido->cliente;

            $pedido['valor_total'] = $pedido->valorTotal();

            $cliente->notify(new NovoPedido($pedido));



            return response()->json($pedido, 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage(), 'trace' => $e->getTrace()], 400);
        }
    }


    public function show(Pedido $pedido)
    {
        $pedido = Pedido::with('produtos')->find($pedido->id);

        return new PedidoResource($pedido);
    }


    public function update(Request $request, Pedido $pedido)
    {
        try {
            $pedido->update($request->all());
            return response()->json($pedido, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao atualizar pedido', 'error' => $e->getMessage()], 400);
        }
    }


    public function destroy(Pedido $pedido)
    {

        try {
            DB::beginTransaction();

            $produtoIds = $pedido->produtos()->pluck('produto_id')->toArray();

            $pedido->produtos()->updateExistingPivot($produtoIds, ['deleted_at' => now()]);

            $pedido->delete();

            DB::commit();

            return response()->json(['message' => 'Pedido excluído com sucesso'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage(), 'trace' => $e->getTrace()], 400);
        }
    }
}
