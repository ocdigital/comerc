<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\PedidoResource;
use App\Notifications\NotificarNovoPedido;

class PedidoController extends Controller
{
    /**
     *  Exibe todos os pedidos com paginação.
     *  Utilizando eager loading para carregar os produtos do pedido.
     *  Utilizando PedidoResource para formatar a resposta.
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $pedidos = Pedido::with('produtos')->paginate(10);
        return PedidoResource::collection($pedidos);
    }

    /**
     *  Cria um novo pedido.
     *  Utilizando transação para garantir que o pedido só será criado se todos os produtos forem criados.
     *  Notifica o cliente sobre o novo pedido.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $pedido = Pedido::create([
                'codigo_cliente' => $request->codigo_cliente,
            ]);

            foreach ($request->produtos as $produto) {
                $pedido->produtos()->attach($produto['produto_id'], ['quantidade' => $produto['quantidade']]);
            }

            DB::commit();

            $cliente = $pedido->cliente;

            $cliente->notify(new NotificarNovoPedido($pedido));

            return response()->json(['pedido' => $pedido, 'produtos' => $pedido->produtos], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage(), 'trace' => $e->getTrace()], 400);
        }
    }

    /**
     *  Exibe um pedido específico.
     *  Utilizando PedidoResource para formatar a resposta.
     *  Utilizando eager loading para carregar os produtos do pedido.
     * @param  \App\Models\Pedido  $pedido
     * @return \Illuminate\Http\Response
     */

    public function show(Pedido $pedido)
    {
        $pedido = Pedido::with('produtos')->find($pedido->id);

        return new PedidoResource($pedido);
    }

    /**
     *  Atualiza um pedido específico.
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pedido  $pedido
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $pedido = Pedido::findOrFail($id);

            $pedido->update([
                'codigo_cliente' => $request->codigo_cliente,
            ]);

            $pedido->produtos()->detach();

            foreach ($request->produtos as $produto) {
                $pedido->produtos()->attach($produto['produto_id'], ['quantidade' => $produto['quantidade']]);
            }

            $pedido->load('produtos');

            DB::commit();

            return response()->json($pedido, 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage(), 'trace' => $e->getTrace()], 400);
        }
    }


    /**
     *  Exclui um pedido específico.
     *  Utilizando transação para garantir que o pedido só será excluído se todos os produtos forem excluídos.
     *  Utilizando soft delete para manter o histórico de pedidos.
     * @param  \App\Models\Pedido  $pedido
     * @return \Illuminate\Http\Response
     */

    public function destroy(Pedido $pedido)
    {
        try {
            DB::beginTransaction();

            $produtoIds = $pedido->produtos()->pluck('pedido_id')->toArray();

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
